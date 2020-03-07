<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  /**
   * The status constant attributes in an associative array.
   *
   * The available statuses are :
   * - draft
   * - sent
   * - partial
   * - complete
   *
   * @var array
   */
  const STATUS = array(
    'draft' => "DRAFT",
    'sent' => "SENT",
    'partial' => "PARTIAL",
    'complete' => "COMPLETE",
  );

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['user', 'total', 'status'];

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
   * The items in the order '1-2-M'.
   *
   * @return void
   */
  public function items()
  {
      return $this->belongsToMany('App\Item', 'order_items', 'order_id', 'item_id');
  }
}
