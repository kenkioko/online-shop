<?php

namespace App\Http\Controllers\USSD\App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Model\Phone;
use App\User;

/**
 * USSD register user or login with the phone number.
 */
trait USSDRegister
{
    use ValidationErrors;

    /**
     * Login a new phone number and sync with user
     *
     * @param string $phone_number
     * @param string $input_text
     * @return \Illuminate\Http\Response
     */
    protected function login_ussd($phone_number, $input_text)
    {
        $response = null;
        $user_input = explode('*', $input_text);

        // prompt for email and password
        switch (count($user_input)) {
          case 1:
            // get email.
            $response_data = "Please enter your email";
            $response = $this->server_response($response_data);
            break;

          case 2:
            // get password.
            $response_data = "Please enter your password";
            $response = $this->server_response($response_data);
            break;

          default:
            // login and sync phone number with user
            $response = $this->login_and_sync($phone_number, [
              'email' => $user_input[1],
              'password' => $user_input[2],
            ]);
            break;
        }

        return $response;
    }

    /**
     * Login using the user data given
     *
     * @param string $phone_number
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    private function login_and_sync($phone_number, $data)
    {
        $response = null;
        $validator = Validator::make($data, [
          'email' => ['required', 'email', 'max:50'],
          'password' => ['required', 'string', 'max:50'],
        ]);

        // validate input
        $response = $this->validate_input_show($validator);
        if ($response) {
          return $response;
        }

        // login and sync phone
        if (Auth::guard('communication')->attempt($validator->validate())) {
          DB::transaction(function () use ($phone_number) {
            $phone = new Phone();
            $phone->phone_number = $phone_number;
            Auth::guard('communication')->User()->phone()->save($phone);
          });

          return $this->server_response('Welcome ' .Auth::guard('communication')->User()->name, false);
        }
        // else credentials don't match
        else {
          return $this->server_response("The given credentials don't match any records", false);
        }
    }

    protected function register_ussd($phone_number, $input_text)
    {
       $response = null;
       $user_input = explode('*', $input_text);
       $roles = [
         "1"  => "Normal Customer",
         "2"  => "Business",
       ];

       // prompt for name, email and password
       switch (count($user_input)) {
         case 1:
           // get name.
           $response_data = "Select your role\n";
           foreach ($roles as $key => $role) {
             $response_data .= "$key. $role\n";
           }

           $response = $this->server_response($response_data);
           break;

         case 2:
           // get name.
           $response_data = "Please enter your name";
           $response = $this->server_response($response_data);
           break;

         case 3:
           // get email.
           $response_data = "Please enter your email";
           $response = $this->server_response($response_data);
           break;

         case 4:
           // get password.
           $response_data = "Please enter your password";
           $response = $this->server_response($response_data);
           break;

         default:
           // get shop data
           $shop_data = null;
           if ($roles[$user_input[1]] === 'Business') {
             switch (count($user_input)) {
               case 5:
                 // get shop name.
                 $response_data = "Please enter business name";
                 $response = $this->server_response($response_data);
                 return $response;

               case 6:
                 // get shop address.
                 $response_data = "Please enter your business location";
                 $response = $this->server_response($response_data);
                 return $response;

               default:
                 // collect the shop data
                 $shop_data = [
                   'shop_name' => $user_input[5],
                   'shop_address' => $user_input[6],
                 ];
                 break;
             }
           }

           // signup and sync phone number with user
           $response = $this->signup_and_sync($phone_number, [
             'role' => $user_input[1],
             'name' => $user_input[2],
             'email' => $user_input[3],
             'password' => $user_input[4],
             'shop_data' => $shop_data,
           ]);
           break;
       }

       return $response;
    }

    /**
     * Create new user using the data given
     *
     * @param string $phone_number
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    private function signup_and_sync($phone_number, $data)
    {
        $response = null;
        $user_validator = Validator::make($data, [
          'name' => ['required', 'string', 'max:50'],
          'email' => ['required', 'email', 'max:50'],
          'password' => ['required', 'string', 'max:50'],
        ]);

        $shop_validator = Validator::make($data['shop_data'], [
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_address' => ['required', 'string', 'max:500'],
        ]);

        $user_data = $shop_data = null;
        // validate user data
        $response = $this->validate_input_show($user_validator);
        if ($response) {return $response;}
        else {$user_data = $user_validator->validate(); }

        // validate shop data
        $shop_data = null;
        if ($data['shop_data']) {
          $response = $this->validate_input_show($shop_validator);
          if ($response) {return $response;}
          else {$shop_data = $shop_validator->validate(); }
        }

        // sign up and sync phone
        $user = DB::transaction(function () use ($phone_number, $user_data, $shop_data) {
          $user = $this->save_user($user_data, $shop_data);
          // sync phone number
          $phone = new Phone();
          $phone->phone_number = $phone_number;
          $user->phone()->save($phone);

          return $user;
        });

        Auth::guard('communication')->login($user);
        return $this->server_response('Welcome ' .Auth::guard('communication')->User()->name, false);
    }

    /**
     * save user and shop (if seller) data the database
     *
     * @param array $user_data
     * @param array $shop_data
     * @return \Illuminate\Http\Response
     */
    private function save_user($user_data, $shop_data=null)
    {
        $role = 'buyer';
        $user = User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'password' => Hash::make($user_data['password']),
        ]);

        // save shop details
        if ($shop_data) {
          $role = 'seller';
          $user->shop()->create([
            'name' => $shop_data['shop_name'],
            'address' => $shop_data['shop_address'],
          ]);
        }

        $user->assignRole($role);
        return $user;
    }

    /**
     * show validation errors if a validator fails
     *
     * @param \Illuminate\Support\Facades\Validator $validator
     * @param mixed
     */
    private function validate_input_show($validator)
    {
      if ($validator->fails()) {
        $response = "There were errors found.\n";
        $response .= $this->getValidationErrors($validator);

        return $this->server_response($response, false);
      }
    }
}
