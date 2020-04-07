<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:orders.create'])->only(['create', 'store']);
        $this->middleware(['permission:orders.update'])->only(['edit', 'update']);
        $this->middleware(['permission:orders.delete'])->only(['delete']);
    }
}
