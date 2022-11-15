<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostType extends Model
{
  use HasFactory; // SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'post_type';
  public $timestamps = false;

  /**
   * Posts
   */
  public function posts(): HasMany
  {
    return $this->hasMany(Post::class, 'type_id', 'id');
  }
}
