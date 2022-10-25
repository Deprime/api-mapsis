<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\{
  SigninController,
  SignupController,
};

use App\Http\Controllers\Api\{
  ProfileController,
  DictionaryController,
  PostController,
};

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
Route::namespace('Api')->group(function() {
  Route::prefix('v1')->group(function () {
    // Authorization
    Route::prefix('auth')->group(function () {
      Route::post('signin',           [SigninController::class, 'signin']);
      Route::post('signin-by-phone',  [SigninController::class, 'signinByPhone']);
      Route::delete('logout',         [SigninController::class, 'logout']);

      Route::post('signup-email',   [SignupController::class, 'signupEmail']);
      Route::post('signup-phone',   [SignupController::class, 'signupPhone']);
      Route::post('send-sms-code',  [SignupController::class, 'sendSmsCode']);
      Route::post('validate-phone', [SignupController::class, 'validatePhone']);
    });

    // Dictionary
    Route::prefix('dictionary')->group(function () {
      Route::get('phone-prefix-list',   [DictionaryController::class, 'phonePrefixList']);
    });

    // Application
    Route::prefix('app')->group(function() {
      Route::group(['middleware' => ['auth:sanctum']], function () {

        // Profile
        Route::prefix('profile')->group(function() {
          Route::get('/',                 [ProfileController::class, 'get']);
          Route::put('/',                 [ProfileController::class, 'update']);
          Route::put('/change-password',  [ProfileController::class, 'changePassword']);
        });

        // Posts
        Route::prefix('posts')->group(function() {
          Route::get('/',             [PostController::class, 'list']);
          Route::get('/{event_id}',   [PostController::class, 'get'])->whereNumber('event_id');
          Route::post('/',            [PostController::class, 'create']);
          Route::put('/{event_id}',   [PostController::class, 'update'])->whereNumber('event_id');
          Route::delete('/{event_id}',[PostController::class, 'delete'])->whereNumber('event_id');
        });
      });
    });
  });
});

