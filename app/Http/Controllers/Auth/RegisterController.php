<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Shop;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_role' => ['required',Rule::in(User::getUserRolesCodes())],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //
        return DB::transaction(function () use ($data) {
          $user = User::create([
              'name' => $data['name'],
              'email' => $data['email'],
              'password' => Hash::make($data['password']),
          ]);
          $user->assignRole($data['user_role']);

          // save shop details
          if ($data['user_role'] === 'seller') {
            $shop_data = $this->validate_shop($data)->validate();
            $user->shop()->create([
              'name' => $shop_data['shop_name'],
              'address' => $shop_data['shop_address'],
            ]);
          }
          return $user;
        });
    }

    /**
     * Validator for \APP\Shop.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validate_shop(array $data)
    {
        return Validator::make($data, [
            'shop_name' => ['required', 'string', 'max:100'],
            'shop_address' => ['required', 'string', 'max:500'],
        ]);
    }
}
