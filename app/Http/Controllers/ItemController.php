<?php

namespace App\Http\Controllers;

use App\Model\Shop;
use App\Model\Item;
use App\Model\Category;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

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
        $this->middleware(['permission:items.create'])->only(['create', 'store']);
        $this->middleware(['permission:items.update'])->only(['edit', 'update']);
        $this->middleware(['permission:items.delete'])->only(['delete']);
    }

    /**
     * save category to database.
     *
     * @param  array  $validated
     * @param  \App\Model\Category  $category
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
        $item->shop()->associate(Auth::user()->shop()->firstOrFail());

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
