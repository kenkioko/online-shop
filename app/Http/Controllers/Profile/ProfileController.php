<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the get request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('dash.user_profile')->with([
          'user' => $user,
          'user_phones' => $user->phone()->get(),
          'user_shop' => $user->shop()->first(),
        ]);
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
          'phone_number' => ['nullable', 'string', 'max:50', 'unique:App\Models\Phone,phone_number'],
          'password' => ['nullable', 'string', 'max:50', 'confirmed'],
          'shop_name' => ['nullable', 'string', 'max:100'],
          'shop_address' => ['nullable', 'string', 'max:500'],
        ]);

        // save to db
        DB::transaction(function () use ($request) {
          $user = Auth::user();
          $user->update($request->only('name', 'email'));

          // save telephone number
          if ($request->phone_number) {
            $phone_number = preg_replace('/\s+/', '', $request->phone_number);
            $user->phone()->save(new Phone(['phone_number' => $phone_number]));
          }

          // change password
          if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
          }

          // change shop details
          if ($user->hasRole('seller')) {
            $shop = $user->shop()->firstOrFail();
            $shop->update([
              'name' => $request->shop_name,
              'address' => $request->shop_address,
            ]);
          }
        });

        return back()->with('success', 'User profile edited successfully');
    }
}
