<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\{
  SigninController,
  SignupController,
};

use App\Http\Controllers\Api\{
  ProfileController,
  PostController,
  PhotoController,
  SearchController,
  DictionaryController,
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

    // Search
    Route::prefix('search')->group(function () {
      Route::get('/',           [SearchController::class, 'list']);
      Route::get('/{post_id}',  [SearchController::class, 'get'])->whereNumber('post_id');
    });

    // Dictionary
    Route::prefix('dictionary')->group(function () {
      Route::get('phone-prefix-list',   [DictionaryController::class, 'phonePrefixList']);
      Route::get('category-list',   [DictionaryController::class, 'categoryList']);
    });

    // Application
    Route::prefix('app')->group(function() {
      Route::group(['middleware' => ['auth:sanctum']], function () {

        // Profile
        Route::prefix('profile')->group(function() {
          Route::get('/',                 [ProfileController::class, 'get']);
          Route::put('/',                 [ProfileController::class, 'update']);
          Route::put('/change-password',  [ProfileController::class, 'changePassword']);

          // Referrals
          Route::prefix('referrals')->group(function() {
            Route::get('/',                 [ProfileController::class, 'getReferrals']);
            Route::get('/{ref_id}',         [ProfileController::class, 'getReferralInfo'])->whereNumber('ref_id');
          });
        });

        // Posts
        Route::prefix('posts')->group(function() {
          Route::get('/',                 [PostController::class, 'list']);
          Route::get('/{post_id}',        [PostController::class, 'get'])->whereNumber('post_id');
          Route::post('/',                [PostController::class, 'create']);
          Route::put('/{post_id}',        [PostController::class, 'update'])->whereNumber('post_id');
          Route::delete('/{post_id}',     [PostController::class, 'delete'])->whereNumber('post_id');
          Route::put('/{post_id}/status', [PostController::class, 'setStatus'])->whereNumber('post_id');

          // Photos
          Route::group(['prefix' => '/{post_id}/photos', 'where' => ['post_id' => '[0-9]+']], function () {
            Route::get('/',             [PhotoController::class, 'list']);
            Route::post('/',            [PhotoController::class, 'create']);
            Route::put('{photo_id}',    [PhotoController::class, 'setPoster'])->whereNumber('photo_id');
            Route::delete('{photo_id}', [PhotoController::class, 'delete'])->whereNumber('photo_id');
          });
        });
      });
    });
  });
});

