<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $validated = $this->validateData($request->all());
        DB::transaction(function () use ($validated) {
          // save user details
          $user = Auth::user();
          $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
          ]);

          // change password
          if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
            $user->save();
          }

          // save telephone number
          if ($validated['phone_number']) {
            $user->phone()->save(new Phone([
              'phone_number' => $validated['phone_number'],
            ]));
          }

          // change shop details
          if ($user->hasRole('seller')) {
            $shop = $user->shop()->firstOrFail();
            $shop->update([
              'name' => $validated['shop_name'],
              'address' => $validated['shop_address'],
            ]);
          }
        });

        return back()->with('success', 'User profile edited successfully');
    }

    private function validateData($data)
    {
        if ($data['phone_number']) {
          // validate phone number regex
          Validator::make($data, [
            'phone_number' => 'regex:/^(\+[0-9]{3}\s[0-9]{3}\s[0-9]{6})$/',
          ]);

          // transform phone number
          $phone_number = preg_replace('/\s+/', '', $data['phone_number']);
          $data['phone_number'] = $phone_number;
        }

        return Validator::make($data, [
          'name' => ['nullable', 'string', 'max:50'],
          'email' => ['nullable', 'email', 'max:100'],
          'password' => ['nullable', 'string', 'max:50', 'confirmed'],
          'shop_name' => ['nullable', 'string', 'max:100'],
          'shop_address' => ['nullable', 'string', 'max:500'],
          'phone_number' => ['nullable','unique:App\Models\Phone,phone_number'],
        ])->validate();
    }
}
