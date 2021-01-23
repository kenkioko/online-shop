<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use App\Models\Address;
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
        $delivery_addresses= $user->address()
            ->orderBy('primary_address', 'desc')
            ->get();

        return view('dash.user_profile')->with([
          'user' => $user,
          'delivery_addresses' => $delivery_addresses,
          'primary_address' => new Address,
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
        $validated_address = $this->validateAddressData($request->all());

        $validated_shop_address = array();
        if (Auth::user()->hasRole('seller')) {
          $validated_shop_address = $this->validateAddressData($request->all(), true);
        }

        DB::transaction(function () use ($validated, $validated_address, $validated_shop_address) {
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

          // save delivery address
          if (!empty($validated_address)) {
            $delivery_address = new Address([
              'country' => $validated_address['address_country'], 
              'state' => $validated_address['address_state'], 
              'city' => $validated_address['address_city'], 
              'street' => $validated_address['address_street'], 
              'postcode' => $validated_address['address_postcode'], 
              'full_address' => $validated_address['full_address'],
            ]);

            // set primary address
            if ($user->address()->count() == 0) {
              $delivery_address->primary_address = true;
            }

            $user->address()->save($delivery_address);
          }

          // change shop details
          if ($user->hasRole('seller')) {
            $shop = $user->shop()->firstOrFail();
            $shop->update([
              'name' => $validated['shop_name'],
            ]);

            // save shop address
            if (! empty($validated_shop_address)) {
              $shop_address = new Address([
                'country' => $validated_shop_address['shop_address_country'], 
                'state' => $validated_shop_address['shop_address_state'], 
                'city' => $validated_shop_address['shop_address_city'], 
                'street' => $validated_shop_address['shop_address_street'], 
                'postcode' => $validated_shop_address['shop_address_postcode'], 
                'full_address' => $validated_shop_address['shop_full_address'],
              ]);
  
              $shop_address->primary_address = true;
              $shop->address()->save($primary_address);
            }            
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
          ])->validate();

          // transform phone number
          $phone_number = preg_replace('/\s+/', '', $data['phone_number']);
          $data['phone_number'] = $phone_number;
        }

        return Validator::make($data, [
          'name' => ['nullable', 'string', 'max:50'],
          'email' => ['nullable', 'email', 'max:100'],
          'password' => ['nullable', 'string', 'max:50', 'confirmed'],
          'shop_name' => ['nullable', 'string', 'max:100'],
          'phone_number' => ['nullable','unique:App\Models\Phone,phone_number'],
        ])->validate();
    }

    private function validateAddressData($data, $shop_address = false)
    {
        $prefix = ($shop_address == true) ? "shop_" : "";
        $rules = [
          "${prefix}address_country" => ['nullable', 'string'],
          "${prefix}address_state" => ['nullable', 'string'],
          "${prefix}address_city" => ['nullable', 'string'],
          "${prefix}address_street" => ['nullable', 'string'],
          "${prefix}address_postcode" => ['nullable', 'string'],
          "${prefix}full_address" => ['required', 'string'],
        ];

        // check if address is set
        $is_address_set = false;
        foreach ($rules as $key => $value) {
          if (isset($data[$key])) {
            $is_address_set = true;
          }
        }

        return $is_address_set ? Validator::make($data, $rules)->validate() : array();
    }
}
