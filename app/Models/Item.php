<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithStatus;

class Item extends Model
{
  use WithStatus;

  /**
   * The order processing status of an item by the seller.
   * Attributes in an associative array.
   *
   * The available statuses are :
   * - queue
   * - received
   * - preparing
   * - sending
   * - finished
   *
   * @var array
   */
  const STATUS = array(
    'queue' => "ITEM IN QUEUE",
    'reject' => "ORDER FOR ITEM REJECTED",
    'received' => "RECEIVED ORDER FOR ITEM",
    'preparing' => "PREPARING ITEM",
    'sending' => "SENDING ITEM",
    'finished' => "ORDER WAS FINALIZED",
  );

  /**
   * The type of item. Either 'product' or 'service'.
   *
   * @var array
   */
  const TYPE = array(
    'product' => "PRODUCT",
    'service' => "SERVICE",
  );

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

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['images_folder'];  

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'trade_allowed' => 'boolean',
    'bid_allowed' => 'boolean',
    'discount_amount' => 'double', 
    'discount_percent' => 'float', 
    'price' => 'double', 
  ];

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'trade_allowed' => false,
    'bid_allowed' => false,
    'discount_amount' => 0.00, 
    'discount_percent' => 0.00, 
  ];

  /**
   * The item's category '1-2-1'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongTo
   */
  public function category()
  {
      return $this->belongsTo('App\Models\Category');
  }

  /**
   * The item's category '1-2-1'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongTo
   */
  public function shop()
  {
      return $this->belongsTo('App\Models\Shop');
  }
}
