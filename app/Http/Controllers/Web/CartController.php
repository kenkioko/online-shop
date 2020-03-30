<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Item;
use App\Model\Order;
use App\Pivot\OrderItem;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;

class CartController extends Controller
{
    /**
     * The currently openned order.
     * The openned order is used as cart with cart items.
     *
     * @var App\Model\Order
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
        $this->middleware(['permission:cart.view'])->only(['index', 'show']);
        $this->middleware(['permission:cart.create'])->only(['create', 'store']);
        $this->middleware(['permission:cart.update'])->only(['edit', 'update']);
        $this->middleware(['permission:cart.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->getOpennedOrder();
        return view('web.cart')->with('cart', $this->open_order);
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
    public function store(CartStoreRequest $request)
    {
        $this->getOpennedOrder();
        $validated = $request->validated();
        $item = Item::findOrFail($validated['item_id']);

        if ($this->saveCartItem($item, $validated['quantity'])) {
          return back()->with('success', 'Item successfully added to the cart');
        }

        return back()->with('error', 'An error occurred while adding item to the cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id the id of the \App\Model\Order
     * @return \Illuminate\Http\Response
     */
    public function update(CartUpdateRequest $request, $id)
    {
        $this->getOpennedOrder();
        $validated = $request->validated();
        $item = Item::findOrFail($validated['item_id']);

        // Item already in current order
        if ($this->open_order->items()->find($item->id) and $validated['update_type'] == 'add') {
          return back()->with('error', 'Item already in cart');
        }

        if($this->saveCartItem($item, $validated['quantity'],  $validated['update_type'])){
          return back()->with('success', 'Item successfully added to the cart');
        }

        return back()->with('error', 'An error occurred while adding item to the cart');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->getOpennedOrder();
        $deleted =  DB::transaction(function () use ($id) {
          $item = $this->open_order->items()->findOrFail($id);
          $deleted = $this->open_order->items()->detach($item);

          $this->open_order->total = $this->getOrderTotal($this->open_order->items);
          return $this->open_order->save();
        });

        if ($deleted) {
          return back()->with('success', 'Item successfully removed from the cart');
        }

        return back()->with('error', 'An error occurred while removing item from the cart');
    }

    /**
     * get the openned order..
     */
    private function getOpennedOrder()
    {
        $open_order = Order::where('user_id', Auth::user()->id)
            ->where('status', Order::getStatus('items_in_cart'))
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
     * @param  \App\Model\Item  $item
     * @return true/false
     */
    private function saveCartItem($item, $quantity, $update_type='add')
    {
        return DB::transaction(function () use ($item, $quantity, $update_type) {
          // save $open_order
          $item_price = $this->getItemPrice($item->price, $item->discount_amount, $quantity);
          if (!$this->open_order->id and $update_type == 'add') {
            $this->open_order->order_no = Uuid::generate()->string;
            $this->open_order->user()->associate(Auth::user());
          }

          if ($this->open_order->save()) {
            $temp_item = [
              'amount' => $item_price,
              'quantity' => $quantity,
            ];

            if ($update_type == 'add') {
              $this->open_order->items()->save($item, $temp_item);
            } else {
              $this->open_order->items()->updateExistingPivot($item->id, $temp_item);
            }

            $this->open_order->total = $this->getOrderTotal($this->open_order->items);
            return $this->open_order->push();
          }
        }, 5);
    }

    /**
     * calculate the item's price
     *
     * @param  double $price
     * @param  double $discount
     * @param  int $quantity
     * @return true/false
     */
    private function getItemPrice($price, $discount, $quantity)
    {
        return ($price - $discount) * $quantity;
    }

    /**
     * calculate the orders total
     *
     * @param  double $items
     * @return int total
     */
    private function getOrderTotal($items)
    {
        $total = 0;
        foreach ($items as $index => $item) {
          $item_price = $this->getItemPrice(
            $item->price,
            $item->discount_amount,
            $item->pivot->quantity
          );

          $this->open_order->items()->updateExistingPivot($item->id, [
            'amount' => $item_price,
          ]);

          $total += $item_price;
        }

        return $total;
    }
}
