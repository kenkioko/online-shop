<?php

namespace App\Http\Controllers\Dash;

use App\Shop;
use App\Item;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\OrderUpdateRequest;
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
        $shop_id = $this->getOwnShop()->id;
        $order_items = OrderItem::whereHas('item', function (Builder $query) use ($shop_id) {
          $query->where('shop_id', $shop_id);
        })->whereHas('order', function (Builder $query) {
          $query->where('status', '!=', Order::getStatus('items_in_cart'));
        })->get();

        return view('dash.orders')->with('orders', $order_items);
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
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // $shop_id = $this->getOwnShop()->id;
        // $order_items = OrderItem::whereHas('item', function (Builder $query) use ($shop_id) {
        //   $query->where('shop_id', $shop_id);
        // })->whereHas('order', function (Builder $query) use ($order) {
        //   $query->where('order_no', $order->order_no);
        // })->get();

        $order_items = $this->getOrderItems($order->order_no);

        return view('dash.order_form')->with([
          'order' => $order,
          'order_items' => $order_items,
          'shop' => $this->getOwnShop(),
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
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * request should be a json with each item's current and changed statuses.
     *
     * @param  \Illuminate\Http\Request  $request // request should be a json.
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $validated = $request->validated();
        $message = DB::transaction(function () use ($order, $validated) {
          $ret_value = function ($code, $message) {
            return $value = [
              'code' => $code,
              'message' => $message,
            ];
          };

          $changed = false;
          $order_status = Order::getStatus('completed');
          foreach (json_decode($validated['status'], true) as $key => $item) {
            $order_item = OrderItem::findOrFail($item['id']);
            // get current status
            if ($item['current_status'] !== $order_item->status) {
              return $ret_value('error', 'Order current status is off.');
            }
            // get changed status
            if (!isset($item['changed_status'])) {
              continue;
            }

            if ($item['changed_status'] !== $order_item->status) {
              $changed = true;
            }
            // change order status
            if ($item['changed_status'] !== Item::getStatus('sending')) {
              $order_status = Order::getStatus('processing');
            }
            // change and save order item status
            $order_item->status = Item::getStatus($item['changed_status']);
            if (!$order_item->save()) {
              return $ret_value('error', 'Something went wrong saving item.' .$order_item->name);
            }
          }

          // nothing changed
          if (!$changed) {
            return $ret_value('error', 'Nothing was changed.');
          }
          // save order
          $order->status = $order_status;
          if (!$order->save()) {
            return $ret_value('error', 'Something went wrong saving order no.' .$order->order_no);
          }

          return $ret_value('success', 'The order was saved successfully.');
        });

        return back()->with($message['code'], $message['message']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        dd('delete', $order);
    }

    /**
     * Returns the shop that corresponds to the loged in user.
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

    private function getOrderItems($order_no)
    {
        $shop_id = $this->getOwnShop()->id;

        return OrderItem::whereHas('item', function (Builder $query) use ($shop_id) {
          $query->where('shop_id', $shop_id);
        })->whereHas('order', function (Builder $query) use ($order_no) {
          $query->where('order_no', $order_no);
        })->get();
    }
}
