<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [''];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['name', 'address'];

    /**
     * The item's category '1-2-1'.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
