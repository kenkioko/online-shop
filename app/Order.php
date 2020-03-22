<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithStatus;

class Order extends Model
{
  use WithStatus;

  /**
   * The status constant attributes in an associative array.
   *
   * The available statuses are :
   * - items_in_cart
   * - order_made
   * - processing
   * - enroute
   * - delivered
   * - rejected
   *
   * @var array
   */
  const STATUS = array(
    'items_in_cart' => "ITEMS IN CART",
    'order_made' => "ORDER MADE",
    'processing' => "PROCESSING ORDER",
    'enroute' => "ORDER ENROUTE",
    'delivered' => "ORDER DELIVERED",
    'rejected' => "ORDER REJECTED",
  );

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['order_no', 'user_id', 'total', 'status'];

  /**
   * The owner of the order '1-2-1'.
   *
   * @return void
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }

  /**
   * The items in the order 'M-2-M'.
   *
   * @return void
   */
  public function items()
  {
      return $this->belongsToMany('App\Item', 'order_item', 'order_id', 'item_id')
          ->withPivot('quantity', 'amount')
          ->withTimestamps();
  }
}
