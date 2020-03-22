<?php

namespace App\Http\Controllers;

use App\Shop;
use App\Item;
use App\Order;
use App\Category;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use Illuminate\Database\Eloquent\Builder;

class ItemController extends Controller
{
    /**
     * The folder in which images are kept.
     *
     * @var const
     */
    protected const item_image_folder = 'item_images';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware(['auth', 'role:admin'])
           ->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (URL::current() === route('admin.items.index')) {

          $items = Item::whereHas('shop', function (Builder $query) {
            $shop_id = Shop::whereHas('user', function (Builder $query) {
              $query->where('id', Auth::user()->id);
            })->firstOrFail()->id;

            $query->where('id', $shop_id);
          })->get();

          return view('dash.items')->with('items', $items);
        }

        return abort(403);
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
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $user = Auth::user();
        $active_order = Order::where('user_id', $user ? $user->id : null)
            ->where('status', Order::getStatus('items_in_cart'))
            ->first();

        return view('item')->with([
          'item' => $item,
          'active_order' => $active_order,
          'files' => $this->get_image_files($item->images_folder),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
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
     * @param  \App\Item  $item
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
     * @param  \App\Item  $item
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

    /**
     * save category to database.
     *
     * @param  array  $validated
     * @param  \App\Category  $category
     * @return boolean
     */
    protected function save($validated, $item, $edit=false)
    {
        if ($edit and isset($validated['images'])) {
          $this->delete_image_files($item->images_folder);
          $path = $this->save_image_files($validated['images']);
          $item->images_folder = $path;
        }

        if (! $edit) {
          $path = $this->save_image_files($validated['images']);
          $item->images_folder = $path;
        }

        $item->name = $validated['name'];
        $item->price = $validated['price'];
        $item->stock = $validated['stock'];
        $item->description = $validated['description'];

        $category = Category::findOrFail($validated['category_id']);
        $item->category()->associate($category);

        return $item->save();
    }

    protected function save_image_files($images)
    {
        $items_folder_name = Uuid::generate()->string;
        foreach($images as $file){
          $upload_path = $this::item_image_folder . '/' . $items_folder_name;
          $file->store($upload_path, 'public');
        }

        return $items_folder_name;
    }

    protected function get_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;
        return Storage::files($directory);
    }

    protected function delete_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;
        return Storage::deleteDirectory($directory);
    }
}
