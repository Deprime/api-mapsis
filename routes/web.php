<?php

use App\Http\Controllers\Api\Auth\SignupController;
use App\Http\Controllers\Web\OAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
  Route::get('telegram',           [OAuthController::class, 'signupByTelegram']);
  Route::get('telegram/callback',  [SignupController::class, 'signupTelegram']);
});

