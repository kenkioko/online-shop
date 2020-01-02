<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The order the items belongs to '1-2-1'.
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     * The items in the order '1-2-M'.
     */
    public function items()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
