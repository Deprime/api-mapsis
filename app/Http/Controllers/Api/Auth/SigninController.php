<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\SmsAero;

use Illuminate\Support\Facades\{
  Hash,
  Validator,
  Auth
};

use App\Services\{
  AuthService,
  ProfileService,
};

use App\Http\Requests\Signin\{
  SigninRequest,
  SigninByPhoneRequest,
};

use App\Models\{
  User,
  SmsCode,
};

class SigninController extends Controller
{
  /**
   * Signin
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signin(SigninRequest $request): JsonResponse
  {
    $input = $request->validated();
    if (!Auth::attempt($input)) {
      return response()->json(['error' => 'The provided credentials are incorrect.'], Response::HTTP_UNAUTHORIZED);
    }

    /** @var User $user */
    $user  = $request->user();
    $token = AuthService::createToken($request, $user);

    // Get profile with relations
    $user = ProfileService::get($user);

    return response()->json([
      'token' => $token,
      'user' => $user,
    ]);
  }

  /**
   * Signin by phone
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signinByPhone(SigninByPhoneRequest $request): JsonResponse
  {
    $input = $request->validated();
    $user = AuthService::findUserByPhone($input['prefix'], $input['phone']);

    if ($user) {
      if (!Auth::attempt($input)) {
        return response()->json(['error' => 'The provided credentials are incorrect.'], Response::HTTP_UNAUTHORIZED);
      }

      $token = AuthService::createToken($request, $user);

      // Get profile with relations
      $user = ProfileService::get($user);

      return response()->json([
        'token' => $token,
        'user'  => $user,
      ]);
    }
    return response()->json(['error' => 'The provided credentials are incorrect.'], Response::HTTP_UNAUTHORIZED);
  }

  /**
   * Logout
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    $user = $request->user();
    if ($user) {
      AuthService::revokeToken($user);
    }
    return response()->json([], Response::HTTP_NO_CONTENT);
  }
}
