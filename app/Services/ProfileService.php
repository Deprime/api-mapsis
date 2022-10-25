<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use \App\Requests\Profile\{
  ProfileUpdateRequest
};

class ProfileService {

  //protected const RELATIONS = ['company', 'realtor_accesses', 'contacts'];

  /**
   * Update current user profile
   *
   * @param ProfileUpdateRequest $request
   * @param User $user
   * @return User
   */
  public function update(ProfileUpdateRequest $request): User
  {
    $input = $request->validated();
    $user  = $request->user();
    $user->update($input);
    return $user;
  }


  /**
   * Create user by phone
   *
   * @param User $user
   * @param string $password
   * @return User
   */
  public static function changePassword(User $user, $password): User
  {
    $user->update(['password' => Hash::make($password)]);
    return $user;
  }

  /**
   * Get user profile with relations
   *
   * @param User $user
   * @return User
   */
  public static function get(User $user): User
  {
    $user->load(static::RELATIONS);
    return $user;
  }
}
