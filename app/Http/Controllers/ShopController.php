<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\AddressStoreRequest;

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

        $shop->name = $validated['shop_details']['shop_name'];
        $shop->address->country = $validated['shop_address']['address_country'];
        $shop->address->state = $validated['shop_address']['address_state'];
        $shop->address->city = $validated['shop_address']['address_city'];
        $shop->address->street = $validated['shop_address']['address_street'];
        $shop->address->postcode = $validated['shop_address']['address_postcode'];
        $shop->address->full_address = $validated['shop_address']['address_full'];

        return $shop->push();
    }

    /**
     * Validate the shop data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function validateData(Request $request) : array
    {
        $shop_req = new ShopStoreRequest;
        $address_req = new AddressStoreRequest;

        return [
          'shop_details' => $request->validate($shop_req->rules()),
          'shop_address' => $request->validate($address_req->rules()),
        ];
    }
}
