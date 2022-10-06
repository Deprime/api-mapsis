<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  public $timestamps = false;

  protected $table = 'role';

  public const DEMENTOR  = 'dementor';
  public const MODERATOR = 'moderator';
  public const CUSTOMER  = 'customer';
  public const PERFORMER = 'performer';

  public const USER_ROLES = [
    self::CUSTOMER,
    self::PERFORMER,
  ];

  public const ADMIN_ROLES = [
    self::DEMENTOR,
    self::MODERATOR,
  ];

  public function users()
  {
    return $this->hasMany(User::class, 'role_id', 'id');
  }
}
