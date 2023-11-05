<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\{
  Hash,
  Validator,
  Auth
};

use App\Services\{
  AuthService,
  ProfileService,
  PhoneValidationService,
};

use App\Http\Requests\Signup\{SignupEmailRequest, SignupPhoneRequest, SignupTelegramRequest};

use App\Models\{
  User,
  Role,
  SmsCode,
};

class SignupController extends Controller
{
  /**
   * Signup via email
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signupEmail(SignupEmailRequest $request): JsonResponse
  {
    $input = $request->validated();

    try {
      $user = AuthService::createUserByEmail($input['email'], $input['password']);
    }
    catch (\Exception $exception) {
      return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY );
    }

    $token = AuthService::createToken($request, $user);
    return response()->json(['token' => $token]);
  }

  /**
   * Signup via phone
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signupPhone(SignupPhoneRequest $request): JsonResponse
  {
    $input = $request->validated();
    $sms_code = SmsCode::query()
      ->where('phone', $input['phone'])
      ->where('prefix', $input['prefix'])
      ->whereNotNull('validated_at')
      ->first();

    if ($sms_code) {
      try {
        $user = AuthService::createUserByPhone($input['phone'], $input['prefix'], $input['password']);
        $user->update([
          'first_name' => "User" . $user->id,
        ]);
      }
      catch (\Exception $exception) {
        return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY );
      }

      // Delete all user's validation codes
      SmsCode::query()
        ->where('phone',  $input['phone'])
        ->where('prefix', $input['prefix'])
        ->delete();

      // Create token
      $token = AuthService::createToken($request, $user);

      // Get profile with relations
      $user = ProfileService::get($user);

      return response()->json([
        'token' => $token,
        'user'  => $user,
      ]);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Signup via Telegram
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signupTelegram(SignupTelegramRequest $request): JsonResponse
  {
    $input = $request->validated();

    // Validate data from TELEGRAM by hash
    $data_check_arr = [];

    foreach ($input as $key => $value) {
      $data_check_arr[] = $key . '=' . $value;
    }

    sort($data_check_arr);

    $data_check_string = implode("\n", $data_check_arr);

    $secret_key = hash('sha256', env('TELEGRAM_TOKEN'), true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);

    if (strcmp($hash, $input['hash']) !== 0) {
      return response()->json(['error' => 'Data is NOT from Telegram'], Response::HTTP_BAD_REQUEST );
    }

    if ((time() - $input['auth_date']) > 86400) {
      return response()->json(['error' => 'Data is outdated'], Response::HTTP_REQUEST_TIMEOUT );
    }

    try {
      $user = AuthService::findUserByTelegram($input['username']);

      if (!$user) {

        $password = "";

        $user = AuthService::createUserByTelegram(
          $input['id'],
          $input['first_name'],
          $input['last_name'],
          $input['username'],
          $password,
          $input['photo_url']
        );
      }

      // Create token
      $token = AuthService::createToken($request, $user);

      // Get profile with relations
      $user = ProfileService::get($user);

      return response()->json([
        'token' => $token,
        'user'  => $user,
      ]);

    }
    catch (\Exception $exception) {
      return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY );
    }
  }
}
