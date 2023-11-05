<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Str;

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
   * Create user by telegram
   * @param $tg_user_id
   * @param $first_name
   * @param $last_name
   * @param $tg_username
   * @param $password
   * @param $photo_url
   * @return User
   */
  public static function createUserByTelegram(string $tg_user_id,string $first_name,string $last_name,string $tg_username, string $password,string $photo_url): User
  {
    $input = [
      'tg_user_id'  => $tg_user_id,
      'first_name'  => $first_name,
      'last_name'   => $last_name,
      'tg_username' => $tg_username,
      'avatar_url'  => $photo_url,
      'password'    => Hash::make($password),
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
    $token_creator = $request->email || $request->phone || $request->username;
    return $user->createToken($token_creator)->plainTextToken;
  }

  /**
   * Revoke current access token
   * @param User $user
   * @return boolean
   */
  public static function revokeToken(User $user): bool
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

  /**
   * Find user by Telegram
   * @param string $tg_username
   * @return User $user | null
   */
  public static function findUserByTelegram(string $tg_username) {
    $user = User::query()
      ->where('tg_username', $tg_username)
      ->first();
    return $user;
  }
  /**
   * Change password
   * @param User $user
   * @param string $password
   * @return User $user | null
   */
  public static function changePassword(User $user, string $password) {
    $input = ['password' => Hash::make($password)];
    $user->update($input);
    return $user;
  }
}
