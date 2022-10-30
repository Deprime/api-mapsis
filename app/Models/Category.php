<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

// use Vehsamrak\Phpluralize\Pluralizer;

use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

class Category extends Model
{
  protected $table = 'category';
  public $timestamps = false;
}
