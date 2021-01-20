<?php

namespace App\Http\Controllers\Web;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page_items = config('items.homepage_rows') * config('items.items_in_row');

        // product items
        $products = Item::where('type', Item::TYPE['product'])
            ->latest()
            ->paginate($page_items);

        // service items
        $services = Item::where('type', Item::TYPE['service'])
            ->latest()
            ->paginate($page_items);

        // return
        return view('web.home', [
            'products' => $products,
            'services' => $services,
        ]);
    }
}
