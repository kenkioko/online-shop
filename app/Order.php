<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
      return $this->belongsToMany('App\Item', 'order_items', 'order_id', 'item_id')
          ->withPivot('quantity', 'amount')
          ->withTimestamps();
  }

  /**
   * Returns the STATUS constant.
   * Can be used to retreive keys only.
   *
   * @param bool $keys_only
   * @return array
   */
  public static function getOrderStatus($keys_only=false)
  {
      $status = self::STATUS;

      if ($keys_only) {
        $status = [];

        foreach(self::STATUS as $key => $value) {
          array_push($status, $key);
        }
      }

      return $status;
  }

  /**
   * Returns a particular status.
   * Either the key or the value is returned specified by a parameter.
   *
   * @param string $$status
   * @param bool $key
   * @return array
   */
  public static function getStatus($status, $key_only=true)
  {
      $status_ = self::STATUS[$status];

      if ($key_only) {
        $status_ = array_keys(self::STATUS, $status);

        foreach(self::STATUS as $key => $value) {
          if ($key == $status) {
            $status_ = $key;
            break;
          }
        }
      }

      return $status_;
  }
}
