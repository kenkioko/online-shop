<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Item;
use App\Models\Category;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
     * Saves the item to the database.
     *
     * @param  array  $validated
     * @param  \App\Models\Category  $category
     * @return boolean
     */
    protected function save($validated, $item, $edit=false)
    {
        // dd('\App\Http\Controllers\ItemController@save', $validated);

        return DB::transaction(function () use ($validated, $item, $edit) {
          if ($edit and isset($validated['images'])) {
            dd('ItemController@save', $this->delete_image_files($item->images_folder));

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
          $item->discount_percent = $validated['discount_percent'];
          $item->discount_amount = $item->price * ($item->discount_percent / 100);
          $item->shop()->associate(Auth::user()->shop()->firstOrFail());

          $category = Category::findOrFail($validated['category_id']);
          $item->category()->associate($category);

          return $item->save();
        });
    }

    /**
     * Saves the image files in storage folder.
     *
     * @param  array  $images
     * @return string   // The folder name
     */
    protected function save_image_files($images)
    {
        $items_folder_name = Uuid::generate()->string;
        foreach($images as $file){
          $upload_path = $this::item_image_folder . '/' . $items_folder_name;
          $file->store($upload_path, 'public');
        }

        return $items_folder_name;
    }

    /**
     * Gets the image files stored in storage folder.
     *
     * @param  array  $images
     * @return \Illuminate\Support\Facades\Storage   // The image folder
     */
    protected function get_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;
        return Storage::files($directory);
    }

    /**
     * Gets the image files stored in storage folder.
     *
     * @param  array  $images
     * @return \Illuminate\Support\Facades\Storage   // The image folder
     */
    protected function delete_image_files($image_folder)
    {
        $directory = 'public/' . $this::item_image_folder . '/' . $image_folder;
        return Storage::deleteDirectory($directory);
    }

    /**
     * Deletes the item in the  database.
     *
     * @param  \App\Models\Item  $item
     * @return boolean
     */
    protected function delete(Item $item)
    {
        return DB::transaction(function () use ($item) {
          $files_delete = $this->delete_image_files($item->images_folder);
          $item_delete = $item->delete();

          return ($files_delete and $item_delete);
        });
    }
}
