<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\Item;
use App\Models\Order;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Base\OrderController as Controller;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::whereHas('user', function (Builder $query) {
          $query->where('id', Auth::user()->id);
        })->where('status', '!=', Order::getStatus('items_in_cart'))
          ->get();

        return view('web.order')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
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
        if (!$order->user()->first()->is(Auth::user())) {
          abort(403);
        }

        if ($order->status != Order::getStatus('items_in_cart')) {
          return back()->with('error', 'Order No: ' .$order->order_no .' cannot be changed.');
        }

        $order->status = Order::getStatus('order_made');
        $order->order_date = now();
        if ($order->save()) {
          return back()->with('success', 'Order No: ' .$order->order_no .' has been made.');
        }

        return back()->with('error', 'Order No: ' .$order->order_no .' occurred an error while processing.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeBid(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('web.order_items')->with([
          'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        abort(404);
    }
}
