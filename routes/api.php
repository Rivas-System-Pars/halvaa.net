<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\themes\DefaultTheme\src\Controllers\FollowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Themes\DefaultTheme\src\Controllers\UserPostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/webhook', [FrontController::class ,]);




require __DIR__.'/api/v1.php';
