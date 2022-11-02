<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\{
  SmsAero,
  HiCall,
};

use Illuminate\Support\Facades\{
  Hash,
  Validator,
  Auth
};

use App\Services\{
  AuthService,
  ProfileService,
};

use App\Http\Requests\Auth\{
  SignupEmailRequest,
  SendSmsCodeRequest,
  ValidatePhoneRequest,
  SignupPhoneRequest,
};

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
   * Send sms code
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendSmsCode(SendSmsCodeRequest $request): JsonResponse
  {
    $input  = $request->validated();
    $number = $input['prefix'] . $input['phone'];
    $code   = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);

    if (config('app.env') === "production") {
      $response = HiCall::call($number);
      $code = $response['code'];
    }

    $sms_code = SmsCode::create([
      'prefix' => $input['prefix'],
      'phone'  => $input['phone'],
      'code'   => $code,
    ]);

    return response()->json([
      'phone' => $input['prefix'] . $input['phone'],
      'code'  => config('app.env') === "production" ? "sent" : $code,
    ]);
  }

  /**
   * Validate phone
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function validatePhone(ValidatePhoneRequest $request): JsonResponse
  {
    $input = $request->validated();
    $number = $input['prefix'] . $input['phone'];

    $sms_code = SmsCode::query()
      ->where('code', $input['code'])
      ->where('phone', $input['phone'])
      ->where('prefix', $input['prefix'])
      ->first();

    if ($sms_code) {
      $sms_code->update(['validated_at' => date("Y-m-d H:i:s")]);
      return response()->json($sms_code);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
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
        ->where('phone', $input['phone'])
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
}
