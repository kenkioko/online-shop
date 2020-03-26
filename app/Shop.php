<?php

namespace App;

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

    /**
     * Returns the shop object.
     * should have a user or will return for the corresponds to the loged in user
     *
     * @param int $user_id
     * @return \App\Shop
     */
    public static function getOwnShop($user=null)
    {
        // dd($user);
        return Shop::whereHas('user', function (Builder $query) use ($user) {
          if (!$user) {
            $user = Auth::user();
          }
          $query->where('id', $user->id);
        })->firstOrFail();
    }
}
