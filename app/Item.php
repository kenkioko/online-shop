<?php

namespace App;

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
    'reject' => "ORDER REJECTED",
    'received' => "RECEIVED ORDER FOR ITEM",
    'preparing' => "PREPARING ITEM",
    'sending' => "SENDING ITEM",
  );

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'category', 'description', 'discount_amount', 'discount_percent'];

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['price', 'images_folder'];

  /**
   * The item's category '1-2-1'.
   *
   * @return void
   */
  public function category()
  {
      return $this->belongsTo('App\Category');
  }

  /**
   * The item's category '1-2-1'.
   *
   * @return void
   */
  public function shop()
  {
      return $this->belongsTo('App\Shop');
  }
}
