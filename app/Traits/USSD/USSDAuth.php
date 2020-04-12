<?php

namespace App\Traits\USSD;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Phone;
use App\User;
use OTP;

/**
 * USSD Trait for user registration and login with the phone number.
 */
trait USSDAuth
{
    use ValidationErrors;

    /**
     * length of otp digits
     * @var int
     */
    private $otp_digits = 5;

    /**
     * validity of the otp in minutes
     * @var int
     */
    private $otp_validity = 5;

    /**
     * Login to use the ussd app using your phone.
     *
     * @param  \App\Mode\Phone  $phone
     * @param  string $input_text
     * @return \App\User
     */
    private function login_ussd_otp($phone, $input_text)
    {
        $user = $phone->user()->first();
        $user_input = explode('*', $input_text);

        // get and validate otp
        $otp = null;
        if (isset($user_input[0]) and $user_input[0]) {
          // validate given pin
          $otp = OTP::validate($user, $user_input[0]);
          if ($otp->status) {
            // login
            Auth::guard('communication')->login($user);
            OTP::extend($user, $user_input[0], 5);
          }
        } else {
          // else create new otp token
          $otp = OTP::generate($user, $this->otp_digits, $this->otp_validity);
        }

        return (object) [
          'user' => Auth::guard('communication')->User(),
          'otp' => $otp,
        ];
    }

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
            $response_data = "Please enter your email address";
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
          $login_status = DB::transaction(function () use ($phone_number) {
            $user = Auth::guard('communication')->User();
            // sync phone
            $phone = new Phone();
            $phone->phone_number = $phone_number;
            $user->phone()->save($phone);
            //generate otp
            $otp = OTP::generate($user, $this->otp_digits, $this->otp_validity);

            return (object) [
              'user' => $user,
              'otp' => $otp,
            ];
          });

          $user = $login_status->user;
          $otp = $login_status->otp;
          if (! $otp->status) {
            $response_data  = "Error while generating One Time Pin (OTP) for $user->name";
            return $this->server_response($response_data, false);
          }

          // success
          $response_data  = "Welcome $user->name \n";
          $response_data .= "Your One Time Pin (OTP) is $otp->token";
          return $this->server_response($response_data, false);
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


       // prompt for name, email and password
       switch (count($user_input)) {
         case 1:
           // get name.
           $response_data = "Select your role\n";
           foreach (User::getUserRoles() as $key => $role) {
             if ($key != User::getUserRoleByCode('admin')['key']) {
               $response_data .= "$key." .$role['name'] ."\n";
             }
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
           $response_data = "Please enter your email address";
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
           if ($this->is_seller($user_input[1])) {
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

           $input_data = [
             'role' => $user_input[1],
             'name' => $user_input[2],
             'email' => $user_input[3],
             'password' => $user_input[4],
             'shop_data' => $shop_data,
           ];

           // display input data for confirmation
           $confirm = $this->confirm_data($user_input, $input_data);
           if (! $confirm) {
             return $this->display_data($input_data);
           }

           // signup and sync phone number with user
           if ($confirm == '1') {
             return $this->signup_and_sync($phone_number, $input_data);
           }

           $response = $this->server_response('Registration Canceled', false);
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
          'email' => ['required', 'email', 'unique:App\User,email', 'max:50'],
          'password' => ['required', 'string', 'max:50'],
        ]);

        $user_data = $shop_data = null;
        // validate user data
        $response = $this->validate_input_show($user_validator);
        if ($response) {return $response;}
        else {$user_data = $user_validator->validate(); }

        // validate shop data
        $shop_data = null;
        if ($data['shop_data']) {
          $shop_validator = Validator::make($data['shop_data'], [
              'shop_name' => ['required', 'string', 'max:255'],
              'shop_address' => ['required', 'string', 'max:500'],
          ]);

          $response = $this->validate_input_show($shop_validator);
          if ($response) {return $response;}
          else {$shop_data = $shop_validator->validate(); }
        }

        // sign up and sync phone
        $sign_up_status = DB::transaction(function () use ($phone_number, $user_data, $shop_data) {
          $user = $this->save_user($user_data, $shop_data);
          // sync phone number
          $phone = new Phone();
          $phone->phone_number = $phone_number;
          $user->phone()->save($phone);
          // login
          Auth::guard('communication')->login($user);

          return (object) [
            'user' =>$user,
            'otp' => OTP::generate($user, $this->otp_digits, $this->otp_validity),
          ];
        });

        $user = $sign_up_status->user;
        $otp = $sign_up_status->otp;
        if (! $otp->status) {
          $response_data  = "Error while generating One Time Pin (OTP) for $user->name";
          return $this->server_response($response_data, false);
        }

        $user = Auth::guard('communication')->User();
        $response_data  = "Welcome $user->name \n";
        $response_data .= "Your One Time Pin (OTP) is $otp->token";

        return $this->server_response($response_data, false);
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

        // return $this->server_response($response, false);
        return $response;
      }
    }

    /**
     * Confirm if all data is availalble from the user input
     *
     * @param array $data
     * @return mixed
     */
    private function confirm_data($user_input, $input_data)
    {
        $confirm = null;
        $user_data_complete = (! $this->is_seller($input_data['role'])) and (count($user_input) >= 6);
        $shop_data_complete = ($this->is_seller($input_data['role'])) and (count($user_input) >= 8);
        if ($user_data_complete or $shop_data_complete) {

          if ($user_data_complete) {
            $confirm = isset($user_input[5]) ? $user_input[5] : false;
          } else if ($shop_data_complete) {
            $confirm = isset($user_input[7]) ? $user_input[7] : false;
          }
        }

        return $confirm;
    }

    /**
     * Display the inputed data by user for confirmation
     *
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    private function display_data($data)
    {
        $response_data  = "Please confirm your data.\n";
        $response_data .= "\n";
        foreach ($data as $data_key => $data_value) {
          if ($data_key == 'shop_data') {
            if (is_array($data_value)) {
              foreach ($data_value as $shop_key => $shop_value) {
                $response_data .= "$shop_key: $shop_value \n";
              }
            }
          } elseif ($data_key == 'role') {
            $response_data .= "$data_key: " .User::getUserRoleByKey($data_value)['name'] ." \n";
          } else {
            $response_data .= "$data_key: $data_value \n";
          }
        }

        // confirmation menu
        $response_data .= "\n";
        $response_data .= "1. Ok \n";
        $response_data .= "2. Cancel ";

        return $this->server_response($response_data);
    }

    private function is_seller($role_key)
    {
        return User::getUserRoleByKey($role_key) === User::getUserRoleByCode('seller');
    }
}
