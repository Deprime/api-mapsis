<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
  use SoftDeletes, HasFactory;

  protected $table = 'pages';

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'language_id',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'language_id',
    'title',
    'content',
    'status',
    'published_at'
  ];

  /**
   * Poster
   */
  public function language()
  {
    return $this->hasOne(Language::class, 'id', 'language_id');
  }

}
