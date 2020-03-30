<?php

namespace App\Http\Controllers\Dash;

use App\Model\Shop;
use App\Model\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\ItemController as Controller;

class ItemController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['permission:items.view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = null;
        if (Auth::user()->hasRole('seller')) {
          $items = Item::whereHas('shop', function (Builder $query) {
            $shop_id = Shop::whereHas('user', function (Builder $query) {
              $query->where('id', Auth::user()->id);
            })->firstOrFail()->id;

            $query->where('id', $shop_id);
          })->get();
        } elseif (Auth::user()->hasRole('admin')) {
          $items = Item::all();
        }

        return view('dash.items')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dash.item_form')->with('item', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemStoreRequest $request)
    {
        if (! $this->save($request->validated(), new Item)) {
          return back()->withInput();
        }

        return redirect()->route('admin.items.index')->with([
          'items' => Item::all(),
          'success' => 'Item added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
      return view('dash.item_form')->with([
        'item' => $item,
        'files' => $this->get_image_files($item->images_folder),
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemUpdateRequest $request, Item $item)
    {
        if (! $this->save($request->validated(), $item, true)) {
          return back()->withInput();
        }

        return redirect()->route('admin.items.index')->with([
          'items' => Item::all(),
          'success' => 'Item edited successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if(! $this->delete_image_files($item->images_folder)) {
          return back()->withInput();
        }

        $item->delete();
        return redirect()->route('admin.items.index')->with([
          'items' => Item::all(),
          'success' => 'Item deleted successfully'
        ]);
    }
}
