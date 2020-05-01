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
        $this->middleware('auth');;
    }

    /**
     * Handle the get request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // show form
        return view('dash.user_profile')->with('user', Auth::user());
    }

    /**
     * Handle the put request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name' => ['nullable', 'string', 'max:50'],
          'email' => ['nullable', 'email', 'max:100'],
          'phone' => ['nullable', 'string', 'max:50'],
          'password' => ['required', 'string', 'max:50', 'confirmed'],
        ]);

        // return back()->with('errors', ['A ']);

        dd($request);
    }
}
