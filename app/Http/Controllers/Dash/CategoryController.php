<?php

namespace App\Http\Controllers\Dash;

use App\Models\Shop;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Controllers\Base\CategoryController as Controller;

class CategoryController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['permission:categories.view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // admin category
        return view('dash.categories')->with([
          'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('dash.category_form')->with([
          'category' => null,
          'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        if (! $this->save($request->validated(), new Category)) {
          return back()->withInput();
        }

        return redirect()->route('admin.categories.index')->with([
          'categories' => Category::all(),
          'success' => 'Category added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $items = null;
        if (Auth::user()->hasRole('seller')) {
          $items = $category->items()->whereHas('shop', function (Builder $query) {
            $shop_id = Shop::whereHas('user', function (Builder $query) {
              $query->where('id', Auth::user()->id);
            })->firstOrFail()->id;

            $query->where('id', $shop_id);
          })->get();
        } elseif (Auth::user()->hasRole('admin')) {
          $items = $category->items()->get();
        }

        return view('dash.category_view')->with([
          'category' => $category,
          'items' => $items,
          'sub_categories' => $category->sub_categories()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('dash.category_form')->with([
          'category' => $category,
          'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        if (! $this->save($request->validated(), $category)) {
          return back()->withInput();
        }

        return redirect()->route('admin.categories.index')->with([
          'categories' => Category::all(),
          'success' => 'Category edited successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->sub_categories()->count() > 0) {
          return back()->with([
            'error' => 'Cannot delete a category with sub categories'
          ]);
        }

        if ($category->items()->count() > 0) {
          return back()->with([
            'error' => 'Cannot delete a category with saved items'
          ]);
        }

        if ($category->delete()) {
          return redirect()->route('admin.categories.index')->with([
            'categories' => Category::all(),
            'success' => 'Category deleted successfully'
          ]);
        }

        return back()->with('error', 'Something went wrong when deteting category ' .$category->name);
    }
}
