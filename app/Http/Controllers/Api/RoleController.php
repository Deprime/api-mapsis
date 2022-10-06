<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;

class RoleController extends Controller
{
  /**
   * Get user list
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request)
  {
    $role_list = User::getRoleList();
    return response()->json(['role_list' => $role_list], 200);
  }
}
