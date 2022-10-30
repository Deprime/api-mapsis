<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

class Photo extends Model
{

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'photo';

  /**
   * The attributes that are mass assignable.
   * @var array<int, string>
   */
  protected $fillable = [
    'post_id',
    'author_id',
    'is_poster',
    'name',
    'extension',
    'url',
  ];

  /**
   * Post
   */
  public function post(): BelongsTo
  {
    return $this->belongsTo(Post::class, 'post_id');
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }
}
