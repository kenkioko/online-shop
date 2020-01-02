<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['user', 'item', 'total'];

  /**
   * The owner of the order '1-2-1'.
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
