<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
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
        $this->middleware(['auth', 'admin'])
             ->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $view_name = 'category';

        // admin category
        if (URL::current() === route('admin.category.index')) {
          $view_name = 'dash.categories';
        }

        return view($view_name)->with('categories', $categories);
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

        return redirect()->route('admin.category.index')->with([
          'categories' => Category::all(),
          'success' => 'Category added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if (URL::current() === route('admin.category.show', [
          'category' => $category
        ])) {
          return redirect()->route('category.show', [
            'category' => $category
          ]);
        }

        $items = Item::where('category_id', $category->id)->get();

        return view('category')->with([
          'category' => $category,
          'items' => $items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
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
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        if (! $this->save($request->validated(), $category)) {
          return back()->withInput();
        }

        return redirect()->route('admin.category.index')->with([
          'categories' => Category::all(),
          'success' => 'Category edited successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Category::where('parent_category_id', $category->id)->count()) {
          return back()->with([
            'error' => 'Cannot delete a category with sub categories'
          ]);
        }

        $category->delete();
        return redirect()->route('admin.category.index')->with([
          'categories' => Category::all(),
          'success' => 'Category deleted successfully'
        ]);
    }

    /**
     * save category to database.
     *
     * @param  array  $validated
     * @param  \App\Category  $category
     * @return boolean
     */
    protected function save($validated, $category)
    {
        $category->name = $validated['name'];
        $parent_category = Category::find($validated['parent_category_id']);
        if ($parent_category) {
          $category->parent_category_id = $parent_category->id;
        }

        return $category->save();
    }
}
