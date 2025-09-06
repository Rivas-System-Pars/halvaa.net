<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginWithCodeController extends Controller
{
    public function create(Request $request)
    {
        $view = config('front.pages.login-with-code');

        if (!$view || option('login_with_code', 'off') == 'off') {
            abort(404);
        }

        return view($view);
    }

    public function store(Request $request)
    {

        $request->validate([
            'phone_number' => 'required|exists:users,phone_number',
            'captcha' => ['required', 'captcha'],
        ], [
            'phone_number.exists' => 'حساب کاربری با شماره موبایل ' . $request->phone_number . ' وجود ندارد. لطفا ثبت نام کنید'
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        PasswordResetLinkController::sendCode($user);


        return response('success');
    }

    public function confirm(Request $request)
    {
        // dd('s');
        $request->validate([
            'phone_number' => 'required|exists:users,phone_number',
            'verify_code' => 'required',
        ]);

        // همه‌ی یوزرهای مرتبط با شماره را بگیر (نه فقط first)
        $users = User::where('phone_number', $request->phone_number)->get();
        $time = Carbon::now()->subMinutes(15);

        // OTP را برای هرکدام از این user_id ها معتبر بدان
        $request->validate([
            'verify_code' => [
                Rule::exists('one_time_codes', 'code')->where(function ($q) use ($users, $time) {
                    $q->whereIn('user_id', $users->pluck('id'))
                        ->where('created_at', '>=', $time);
                }),
            ],
        ], [
            'verify_code.exists' => 'کد وارد شده اشتباه است یا منقضی شده است.',
        ]);
        // dd($users->count());

        if ($users->count() === 1) {
            // تک‌اکانتی: همین‌جا لاگین کن
            $user = $users->first();
            Auth::loginUsingId($user->id, true);
            OneTimeCode::where('user_id', $user->id)->delete();

            return redirect()->route('front.index');
        }

        // چنداکانتی: شماره را در سشن بگذار و بفرست صفحه انتخاب اکانت
        session()->put('phone_number', $request->phone_number);
        self::pickAccount();

        return redirect()->route('front.pick-account');
    }


    public function pickAccount()
    {
        $phone = session('phone_number');
    
        if (!$phone) {
            return redirect()->route('login')->withErrors([
                'phone_number' => 'جلسه منقضی شده یا شماره در دسترس نیست.'
            ]);
        }
    
        $users = User::where('phone_number', $phone)->get();
    
        if ($users->isEmpty()) {
            return redirect()->route('login')->withErrors([
                'phone_number' => 'کاربری با این شماره یافت نشد.'
            ]);
        }

        // dd('s');
    
        return view('front::auth.pick_account', compact('users'));
        // return redirect()->route('front.pick-account');
    }
    

    public function pickAccountSubmit(Request $request)
    {
        $phone = session('phone_number');

        if (!$phone) {
            return redirect()->route('login')->withErrors([
                'phone_number' => 'جلسه منقضی شده است.'
            ]);
        }

        $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('phone_number', $phone)),
            ],
        ]);

        $user = User::findOrFail($request->user_id);

        // عملیات بعد لاگین (همونایی که در حالت تک‌کاربر انجام می‌دادی)
        // $user->update(['force_to_password_change' => true]);
        OneTimeCode::where('user_id', $user->id)->delete();

        Auth::loginUsingId($user->id, true);

        // پاک کردن شماره از سشن
        session()->forget('phone_number');

        return redirect()->route('front.index');
    }
}
