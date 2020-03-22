<?php

namespace App\Http\Controllers\Web;

use App\Item;
use App\Order;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\OrderController as Controller;

class OrderController extends Controller
{
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

        return view('order')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(403);
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
        if ($order->user()->first()->id != Auth::user()->id) {
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
        return view('order_items')->with([
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
        abort(403);
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
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        abort(403);
    }
}
