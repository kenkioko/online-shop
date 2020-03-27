<?php

namespace App\Http\Controllers\Dash;

use App\Shop;
use App\Item;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $shop_id = Shop::getOwnShop()->id;
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
        $order_items = $this->getOrderItems($order->order_no);

        return view('dash.order_form')->with([
          'order' => $order,
          'order_items' => $order_items,
          'shop' => Shop::getOwnShop(),
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
          foreach (json_decode($validated['status'], true) as $key => $item) {
            $order_item = OrderItem::findOrFail($item['id']);

            // get current status
            if ($item['current_status'] !== $order_item->status) {
              return $ret_value('error', 'Order current status is off.');
            }

            // save order items
            if ( $this->save_order_items(
              $order_item,
              isset($item['changed_status']) ? $item['changed_status'] : null
            )) {
              $changed = true;
            } else {
              return $ret_value(
                'error',
                'Something went wrong saving item.' .$order_item->name
              );
            }
          }

          // nothing changed
          if (!$changed) {
            return $ret_value('error', 'Nothing was changed.');
          }

          // save order
          if (!$this->save_order($order)) {
            return $ret_value('error', 'Something went wrong saving order no.' .$order->order_no);
          }

          // success
          return $ret_value('success', 'The order was saved successfully.');
        });

        return back()->with($message['code'], $message['message']);
    }

    /**
     * Save the order items' status in the order.
     *
     * @param $item
     */
    private function save_order_items($order_item, $chgd_status=null)
    {
        $no_error = true;
        // get changed status
        if ($chgd_status and $chgd_status !== $order_item->status) {
          $order_item->status = Item::getStatus($chgd_status);

          if ($chgd_status === Item::getStatus('sending')) {
            $item = $order_item->item()->firstOrFail();
            $item->stock -= $order_item->quantity;
            $item->save();
          }
          $no_error = $order_item->save();
        }

        return $no_error;
    }

    private function save_order($order)
    {
        $no_error = $all_completed = true;
        $order_status = Order::getStatus('completed');
        foreach ($order->items()->get() as $index => $item) {
          $order_received = ($item->pivot->status === Item::getStatus('received'));
          $order_preparing = ($item->pivot->status === Item::getStatus('preparing'));
          $order_sending = ($item->pivot->status === Item::getStatus('sending'));
          $order_in_queue = ($item->pivot->status === Item::getStatus('queue'));
          if ($order_received or $order_preparing or $order_sending or $order_in_queue) {
            // order processing
            $order_status = Order::getStatus('processing');
          } elseif ($item->pivot->status !== Item::getStatus('received')) {
            // order completed state 'partial or total'
            $order_status = Order::getStatus('completed_partial');
          }
        }

        if ($order->status != $order_status) {
          $order->status = $order_status;
          $no_error = $order->save();
        }

        $order->status = $order_status;
        return $no_error;
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

    private function getOrderItems($order_no)
    {
        $shop_id = Shop::getOwnShop()->id;
        return OrderItem::whereHas('item', function (Builder $query) use ($shop_id) {
          $query->where('shop_id', $shop_id);
        })->whereHas('order', function (Builder $query) use ($order_no) {
          $query->where('order_no', $order_no);
        })->get();
    }
}
