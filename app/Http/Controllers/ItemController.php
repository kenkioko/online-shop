<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;

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
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dash.items')->with('items', Item::all());
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

        return redirect()->route('admin.item.index')->with([
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
        return view('item')->with([
          'item' => $item,
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
        if (! $this->save($request->validated(), $item)) {
          return back()->withInput();
        }

        return redirect()->route('admin.item.index')->with([
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
        if(! delete_image_files($item->images_folder)) {
          return back()->withInput();
        }

        $item->delete();
        return redirect()->route('admin.item.index')->with([
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
        $items_folder_name = Uuid::generate()->string;
        foreach($validated['images'] as $file){
          $upload_path = $this::item_image_folder . '/' . $items_folder_name;
          $file->store($upload_path, 'public');
        }

        $item->name = $validated['name'];
        $item->price = $validated['price'];
        $item->stock = $validated['stock'];
        $item->description = $validated['description'];
        $item->images_folder = $items_folder_name;

        $category = Category::findOrFail($validated['category_id']);
        $item->category_id = $category->id;

        return $item->save();
    }

    protected function get_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;

        return Storage::files($directory);
    }

    protected function delete_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;
        dd($directory);

        return Storage::deleteDirectory($directory);
    }
}
