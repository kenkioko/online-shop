<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * The currently loggen in user.
     *
     * @var \App\User
     */
    private $user = null;

    /**
     * Instantiate a new controller instance.
     * Fetch the currently loged in user details
     *
     * @return void
     */
    public function __construct()
    {
        // Get the currently authenticated user...
        $this->user = Auth::user();
    }

    /**
     * Handle the get request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
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
}
