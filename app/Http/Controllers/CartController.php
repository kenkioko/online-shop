<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use App\OrderItem;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;

class CartController extends Controller
{
    /**
     * The currently openned order.
     * The openned order is used as cart with cart items.
     *
     * @var App\Order
     */
    private $open_order = null;

    /**
     * Instantiate a new controller instance.
     * Fetch the currently openned order
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:view cart'])->only(['index', 'show']);
        $this->middleware(['permission:create cart'])->only(['create', 'store']);
        $this->middleware(['permission:update cart'])->only(['edit', 'update']);
        $this->middleware(['permission:delete cart'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->getOpennedOrder();

        // dd($this->open_order->items()->get());

        return view('cart')->with('cart', $this->open_order);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request)
    {
        $this->getOpennedOrder();
        $validated = $request->validated();
        $item = Item::findOrFail($validated['item_id']);

        if ($this->saveCartItem($item)) {
          return back()->with('success', 'Item successfully added to cart');
        }

        return back()->with('error', 'An error occurred while adding item to cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id the id of the \App\Order
     * @return \Illuminate\Http\Response
     */
    public function update(CartUpdateRequest $request, $id)
    {
        $this->getOpennedOrder();
        $validated = $request->validated();
        $item = Item::findOrFail($validated['item_id']);

        // Item already in current order
        if ($this->open_order->items()->find($item->id)) {
          return back()->with('error', 'Item already in cart');
        }

        if($this->saveCartItem($item)){
          return back()->with('success', 'Item successfully added to cart');
        }

        return back()->with('error', 'An error occurred while adding item to cart');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get the openned order..
     */
    private function getOpennedOrder()
    {
        $open_order = Order::where('user_id', Auth::user()->id)
            ->where('status', 'items_in_cart')
            ->first();

        if ($open_order) {
          $this->open_order = $open_order;
        } else {
          $this->open_order = new Order;
        }
    }

    /**
     * save the openned order..
     *
     * @param  \App\Item  $item
     * @return true/false
     */
    private function saveCartItem($item)
    {
        $cart_saved = false;

        // save $open_order
        if ($this->open_order->id) {
          $this->open_order->total += $item->price;
        } else {
          $this->open_order->order_no = Uuid::generate()->string;
          $this->open_order->user()->associate(Auth::user());
          $this->open_order->total = $item->price;
        }

        if ($this->open_order->save()) {
          $this->open_order->items()->save($item);
          $this->open_order->items()->updateExistingPivot($item->id, [
            'amount' => $item->price - $item->discount_amount
          ]);

          $cart_saved = $this->open_order->push();
        }

        return $cart_saved;
    }
}
