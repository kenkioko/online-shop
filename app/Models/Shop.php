<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Order;
use App\Pivot\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The item's owner '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The item's for the shop '1-2-M'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    /**
     * The shop's address '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne('App\Models\Address');
    }

    /**
     * The shop's new orders.
     * @TODO To be replaced by laravel notifications
     *
     * @return mixed
     */
    public function getNewOrders($count=false)
    {
        $shop_id = $this->id;
        $new_orders = OrderItem::whereHas('item', function (Builder $query) use ($shop_id) {
          $query->where('shop_id', $shop_id);
        })->whereHas('order', function (Builder $query) {
          $query->where('status', '!=', Order::getStatus('items_in_cart'));
        })->where('status', Item::getStatus('queue'));

        if ($count) {
          return $new_orders->count();
        }

        return $new_orders->get();
    }
}
