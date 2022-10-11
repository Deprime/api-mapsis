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

class EventStatus extends Model
{
  // use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'event_status';

  /**
   * Events
   */
  public function events(): HasMany
  {
    return $this->hasMany(Event::class, 'status_id', 'id');
  }
}
