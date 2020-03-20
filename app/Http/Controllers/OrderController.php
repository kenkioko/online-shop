<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Database\Eloquent\Builder;

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
          'index', 'show', 'store', 'update'
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
          $orders = Order::where('status', '!=', Order::getStatus('items_in_cart'))->get();
          return view('dash.orders')->with('orders', $orders);
        }

        $orders = Order::whereHas('user', function (Builder $query) {
          $query->where('id', Auth::user()->id);
        })->get();

        return view('order')->with('orders', $orders);
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
        // make order
        $order = Order::where('order_no', $validated['order_number'])->firstOrFail();
        if ($order->user()->first() != Auth::user()) {
          abort(403);
        }

        $order->status = Order::getStatus('order_made');
        if ($order->save()) {
          return back()->with('success', 'Order No: ' .$order->order_no .' has been made.');
        }

        return back()->with('error', 'Order No: ' .$order->order_no .' occurred an error while processing.');
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
        $item = Item::findOrFail($validated['item_id']);

        // Item already in current order
        if ($order->items()->find($item->id)) {
          return back()->with('error', 'Item already in cart');
        }

        $order->total += $item->price;
        $order->items()->save($item);
        $order->items()->updateExistingPivot($item->id, [
          'amount' => $item->price - $item->discount_amount
        ]);

        if($order->push()){
          return back()->with('success', 'Item successfully added to cart');
        }

        return back()->with('error', 'An error occurred adding item to cart');
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
