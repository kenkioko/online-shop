<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use App\OrderItem;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;

class OrderController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:admin'])->except([
          'store', 'update'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // admin orders index
        if (URL::current() === route('admin.orders.index')) {
          $orders = Order::where('status', '!=', 'items_in_cart')->get();
          return view('dash.orders')->with('orders', $orders);
        }

        $orders = Order::has('user')->get();
        return view('cart')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dash.order_form')->with([
          'order' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();
        // new order
        $order = new Order;
        $order->user()->associate(Auth::user());
        $order->order_no = Uuid::generate()->string;
        // order items
        $item = Item::findOrFail($validated['item_id']);
        $order->total = $item->price;
        // save
        if ($order->save()) {
          // order items
          $order_items = new OrderItem;
          $order_items->order()->associate($order);
          $order_items->items()->associate($item);
          $order_items->save();

          return redirect()->route('items.show', ['item' => $item])
            ->with([
              'items' => Item::all(),
              'success' => 'Item successfully added to cart'
            ]);
        }

        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('cart')->with([
          'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('dash.order_form')->with([
          'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $validated = $request->validated();
        dd($validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
