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
   *
   * @var array
   */
  const STATUS = array(
    'queue' => "ITEM IN QUEUE",
    'reject' => "ORDER FOR ITEM REJECTED",
    'received' => "RECEIVED ORDER FOR ITEM",
    'preparing' => "PREPARING ITEM",
    'sending' => "SENDING ITEM",
    'received' => "RECEIVED ITEM",
  );

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'category', 'description', 'discount_amount', 'discount_percent', 'price'
  ];

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['images_folder'];

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
