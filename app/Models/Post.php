<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

use Vehsamrak\Phpluralize\Pluralizer;

use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

class Post extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'post';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'type_id',
    'author_id',
    'status_id',
    'category_id',
    'title',
    'description',
    'address',           // User's manual entered address
    'suggested_address', // Address suggested by Map service geocoder
    'coords',            // Map marker coordinates
    'price',
    'published_at',
    'start_at',
    'finish_at',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'coords'        => 'array',
    'start_at'      => 'datetime',
    'finish_at'     => 'datetime',
    'published_at'  => 'datetime',
    // 'published_at' => 'date:' . self::CUSTOM_DATE_FORMAT,
  ];

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }

  /**
   * Participants
   */
  public function participants(): belongsToMany
  {
    return $this->belongsToMany(User::class, 'post_user', 'user_id', 'post_id');
  }

  /**
   * Post status
   */
  public function status(): BelongsTo
  {
    return $this->belongsTo(PostStatus::class, 'status_id');
  }

  /**
   * Post status
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(PostType::class, 'type_id');
  }

  /**
   * Photos
   */
  public function photos(): HasMany
  {
    return $this->hasMany(Photo::class, 'post_id', 'id');
  }

  /**
   * Poster
   */
  public function poster()
  {
    return $this->hasOne(Photo::class, 'post_id', 'id')
                ->where('is_poster', 1);
                // ->first();
  }

  /**
   * Scope published
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePublished($query)
  {
    return $query->where('status_id', 2);
  }
}
