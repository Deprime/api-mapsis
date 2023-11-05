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
   * @param SignupEmailRequest $request
   * @return JsonResponse
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
   * @param SignupPhoneRequest $request
   * @return JsonResponse
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

}
