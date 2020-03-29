<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['order'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['amount', 'quantity', 'status'];

    /**
     * The order the items belongs to '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     * The items in the order '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
