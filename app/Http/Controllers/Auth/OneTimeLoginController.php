<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OneTimeLoginController extends Controller
{
    public function create(Request $request)
    {
        // dd('');
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|exists:users,phone_number'
        ]);

        $view = config('front.pages.one-time-login');

        if (!$view || $validator->fails()) {
            abort(404);
        }

        $user = User::where('phone_number', $request->phone_number)->first();
        $verify_code = OneTimeCode::where('user_id', $user->id)->latest()->first();

        if (!$verify_code) {
            return redirect()->route('password.request');
        }

        $resend_time = $verify_code->created_at->addSeconds(120)->timestamp;

        return view($view, compact('resend_time', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|exists:users,phone_number',
        ]);

        $users = User::where('phone_number', $request->phone_number)->get();
        $usercount = $users->count();
        $user = $users->first(); 

        $time = Carbon::now()->subMinutes(15);

        $request->validate([
            'verify_code' => [
                'required',
                Rule::exists('one_time_codes', 'code')->where(function ($query) use ($users, $time) {
                    $query->whereIn('user_id', $users->pluck('id'))
                        ->where('created_at', '>=', $time);
                }),
            ]
        ], [
            'verify_code.exists' => 'کد وارد شده اشتباه است'
        ]);

        // ❌ این دو کار را دیگر اینجا انجام نده؛ فقط در حالت تک‌کاربره انجام می‌دهیم
        // $user->update(['force_to_password_change' => true]);
        // OneTimeCode::where('user_id', $user->id)->delete();

        if ($usercount == 1) {

            $user->update([
                'force_to_password_change' => true,
            ]);
            OneTimeCode::where('user_id', $user->id)->delete();

            Auth::loginUsingId($user->id, true);

            return response('success');
        } else {
            session()->put('phone_number', $request->phone_number);

            return redirect()->route('auth.pick_account');
        }
    }

}
