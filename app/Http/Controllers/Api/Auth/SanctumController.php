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
  AuthService
};

use App\Http\Requests\Auth\{
  SigninRequest,
  SignupEmailRequest
};

use App\Models\{
  User,
  Role,
};

class SanctumController extends Controller
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

    return response()->json(['token' => $token, 'user' => $user]);
  }

  /**
   * Signin by phone
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signinByPhone(SigninByPhoneRequest $request): JsonResponse
  {
    $input = $request->validated();
    $user = AuthService::findUserByPhone($input['prefix'], $input['phone'], $input['password']);

    if ($user) {
      $token = AuthService::createToken($request, $user);
      return response()->json([
        'token' => $token,
        'user'  => $user,
      ]);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
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
    return response()->json([], Response::HTTP_NO_CONTEN);
  }
}
