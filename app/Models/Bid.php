<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['amount'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [ 
    'amount' => 'double', 
  ];

  /**
   * The bid's owner '1-2-1'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongTo
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }

  /**
   * The item being bidded '1-2-1'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongTo
   */
  public function item()
  {
      return $this->belongsTo('App\User');
  }
}
