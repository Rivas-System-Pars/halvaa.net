<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class InstallController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        return view('back.auth.register');
    }
    public function ShowUserRegisterForm(Request $request)
    {
        return view('back.auth.userregister');

    }

    public function register(Request $request)
    {

// dd('a');
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'username' => 'required|string|max:191',
            'phone_number' => 'required', 'regex:/^09\d{9}$/',
            'national_code' => 'required', 'regex:/^\d{10}$/',
            'birth' => 'required', 'date', 'before:today',
            'death' => 'required', 'date', 'after:birth',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'admin_route_prefix' => 'required|string',
            'is_private' => 'nullable', 'in:1',

        ]);

        dd('a');
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'birth' => $request->birth,
            'death' => $request->death,
            'national_code' => $request->national_code,
            'level' => 'creator',
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
            'verified_at' => Carbon::now(),
            'is_private' => $request->has('is_private') ? 1 : 0,

        ]);

        change_env('ADMIN_ROUTE_PREFIX', $request->admin_route_prefix);

        $this->configApplication();

        Auth::loginUsingId($user->id);

        return response()->json(['admin_prefix' => $request->admin_route_prefix]);
    }

    private function configApplication()
    {
        AppServiceProvider::loadTheme();

        Artisan::call('shop:link');
        Artisan::call('theme:config');

        change_env('SESSION_DRIVER', 'database');
        change_env('QUEUE_CONNECTION', 'database');
        option_update('app_debug_mode', 'on');

        Artisan::call('optimize');

        try {
            Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get(config('general.api_url') . '/shop/installed', [
                        'host' => url('/') ?: config('app.url'),
                        'time' => now(),
                        'script' => 'shop',
                        'server_ip' => request()->server('SERVER_ADDR'),
                        'installer_ip' => request()->ip(),
                    ]);
        } catch (Exception $e) {
            // just continue
        }
    }


}
