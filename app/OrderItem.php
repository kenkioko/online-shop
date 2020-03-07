<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['amount'];

    /**
     * The order the items belongs to '1-2-1'.
     *
     * @return void
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     * The items in the order '1-2-1'.
     *
     * @return void
     */
    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
