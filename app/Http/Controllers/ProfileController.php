<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Instantiate a new controller instance.
     * Fetch the currently loged in user details
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['permission:view dashboard'])->only('show');
    }

    /**
     * Handle the get request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //Auth::user();
        $this->resist();

    }

    /**
     * Handle the put request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    private function resist()
    {
        // code...
    }
}
