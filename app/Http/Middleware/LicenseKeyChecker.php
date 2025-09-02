<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class LicenseKeyChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
	// 	$license_key = DB::table('apikeys')->where('description','license_key')->first();
	// 	if(!$license_key){
	// 		return response()->json('سایت فاقد کلید لایسنس میباشد لفطا با پشتیبانی تماس حاصل فرمایید');
	// 	}
	// 	$req = Http::withOptions(['verify' => false])
    // ->post('https://license.rivasit.com/api/checkExpire', [
    //     'license_key' => $license_key->key,
    // ]);

	// 	$res = $req->json();

	// 	if ($res['status'] == true) {
	// 		return $next($request);
	// 	}

	// 	return response()->json('سایت منقضی شده است');
    return $next($request);

    }
}
