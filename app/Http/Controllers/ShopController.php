<?php

namespace App\Http\Controllers;

use App\Model\Shop;
use Illuminate\Http\Request;

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

    public static function saveShopDetails($validated, $user)
    {
        $shop = ($user->shop()->count() > 0) ? $user->shop()->first() : new Shop;

        $shop->name = $validated['shop_name'];
        $shop->address = $validated['shop_address'];
        $user->shop()->save($shop);

        return true;
    }

    public static function validateData($request)
    {
        return $request->validate([
          'shop_name' => 'required|string|max:255',
          'shop_address' => 'required|string|max:500'
        ]);
    }
}
