<?php

namespace App\Http\Controllers\Web;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Controllers\Base\ItemController as Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProduct()
    {
        $items = Item::where('type', Item::TYPE['product'])
            ->latest()
            ->get();

        return view('web.item_index', [
            'items' => $items,
            'page_title' => 'Products',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexService()
    {
        $items = Item::where('type', Item::TYPE['service'])
            ->latest()
            ->get();

        return view('web.item_index', [
            'items' => $items,
            'page_title' => 'Services',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemStoreRequest $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $user = Auth::user();
        $active_order = Order::where('user_id', $user ? $user->id : null)
            ->where('status', Order::getStatus('items_in_cart'))
            ->first();

        $related_items = Item::where([
            'type' => $item->type,
            'category_id' => $item->category->id
        ])->take(config('items.items_in_row'))
        ->get();

        return view('web.item')->with([
          'item' => $item,
          'shop' => $item->shop()->firstOrFail(),
          'active_order' => $active_order,
          'files' => $this->get_image_files($item->images_folder),
          'related_items' => $related_items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemUpdateRequest $request, Item $item)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        return abort(404);
    }
}
