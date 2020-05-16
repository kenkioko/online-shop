<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\ShopUpdateRequest;

class ShopController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:shops.create'])->only(['create', 'store']);
        $this->middleware(['permission:shops.update'])->only(['edit', 'update']);
        $this->middleware(['permission:shops.delete'])->only(['delete']);
    }

    /**
     * Save the shop data to db.
     *
     * @param  array $validated
     * @param  \App\User  $user
     * @return boolean
     */
    public static function saveShopDetails(array $validated, User $user)
    {
        $shop = ($user->shop()->count() > 0)
            ? $user->shop()->first()
            : new Shop;

        dd('saveShopDetails', $validated, $user->shop);

        $shop->name = $validated['shop_name'];
        $shop->address = $validated['shop_address'];
        $user->shop()->save($shop);

        return true;
    }

    /**
     * Validate the shop data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function validateData(Request $request) : array
    {
        $shop_req = new ShopUpdateRequest;
        return $request->validate($shop_req->rules());
    }
}
