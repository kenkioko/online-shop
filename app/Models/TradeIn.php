<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeIn extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 
    'description', 
    'discount_amount', 
    'discount_percent', 
    'price', 
    'type', 
    'trade_allowed',
    'bid_allowed',
  ];
}
