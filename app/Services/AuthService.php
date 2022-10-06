<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;

class AuthService {

  /**
   * Create user
   * @param string $email
   * @param string $password
   * @return User
   */
  public static function createUserByEmail($email, $password): User
  {
    $input = [
      'email'    => $email,
      'password' => Hash::make($password),
    ];
    return User::create($input);
  }


  /**
   * Create user by phone
   * @param string $phone
   * @param string $password
   * @return User
   */
  public static function createUserByPhone($phone, $prefix, $password): User
  {
    $input = [
      'phone'    => $phone,
      'prefix'   => $prefix,
      'password' => Hash::make($password),
      'phone_verified_at' => date("Y-m-d H:i:s"),
    ];

    return User::create($input);
  }

  /**
   * Create sanctum token
   * @param User $user
   * @return string
   */
  public static function createToken(Request $request, User $user): string
  {
    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
    }
    $token_creator = $request->email || $request->phone;
    return $user->createToken($token_creator)->plainTextToken;
  }

  /**
   * Revoke current access token
   * @param User $user
   * @return boolean
   */
  public static function revokeToken(User $user): boolean
  {
    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
      return true;
    }
    return false;
  }

  /**
   * Find user by phone
   * @param string $prefix
   * @param string $phone
   * @return User $user | null
   */
  public static function findUserByPhone(string $prefix, string $phone) {
    $user = User::query()
      ->where('prefix', $prefix)
      ->where('phone', $phone)
      ->first();
    return $user;
  }
}
