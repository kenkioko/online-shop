<?php

namespace App\Http\Controllers;

use App\Model\Item;
use App\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:categories.create'])->only(['create', 'store']);
        $this->middleware(['permission:categories.update'])->only(['edit', 'update']);
        $this->middleware(['permission:categories.delete'])->only(['delete']);
    }

    /**
     * save category to database.
     *
     * @param  array  $validated
     * @param  \App\Model\Category  $category
     * @return boolean
     */
    protected function save($validated, $category)
    {
        $category->name = $validated['name'];
        $parent_category = Category::find($validated['parent_category_id']);
        if ($parent_category) {
          $category->parent_category()->associate($parent_category);
        }

        return $category->save();
    }
}
