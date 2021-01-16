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
        $products = Item::where('type', Item::TYPE['product'])
            ->latest()
            ->paginate(16);

        $services = Item::where('type', Item::TYPE['service'])
            ->latest()
            ->paginate(16);

        return view('web.home', [
            'products' => $products,
            'services' => $services,
            'rows' => 5,            
            'row_cols' => 4,
            'row_items' => 8,
        ]);
    }
}
