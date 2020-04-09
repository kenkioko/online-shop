<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\Controller;
use App\Model\USSD;
use App\Model\Phone;
use App\Traits\USSD\USSDRegister;
use App\Traits\USSD\USSDAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class USSDController extends Controller
{
    use USSDRegister, USSDAccount;

    /**
     * Return the validation rules for the africastkng api.
     *
     * @param  array  $data   \\ Data to validate from the api
     * @return Illuminate\Support\Facades\Validator
     */
    protected function validator($data)
    {
        $validator = Validator::make($data, [
          'sessionId' => ['required', 'string', 'max:255'],
          'phoneNumber' => ['required', 'string', 'max:255'],
          'networkCode' => ['required', 'string', 'max:255'],
          'serviceCode' => ['required', 'string', 'max:255'],
          'text' => ['nullable', 'string', 'max:255'],
        ]);

        return $validator;
    }

    /**
     * Return the server response using the USSD protocal.
     *
     * @param  string  $response
     * @param  bool  $alive   // should the session be kept alive
     * @param  int  $code     // http code
     * @return \Illuminate\Http\Response
     */
    protected function server_response($response, $alive=true, $code=200)
    {
        // Echo the response back to the API
        $concat = $alive ? 'CON ' : 'END ';
        return response($concat .$response, $code)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * This is the starting point for the ussd processing.
     *
     * @param  string  $phoneNumber
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    protected function run($phoneNumber, $text='')
    {
        $response = null;
        $text = rtrim($text, "#");
        // check if phone number is associated with any user
        $phone = Phone::where('phone_number',$phoneNumber)->first();
        if ($phone) {
          // auto login first
          $user = $this->login_ussd_auto($phone);
          $response = $this->main_menu($user, $text);
        } else {
          $response = $this->register_menu($phoneNumber, $text);
        }

        if (! $response) {
          $response = $this->server_response('Error Session Ended', false);
        }
        return $response;
    }

    /**
     * Login to use the ussd app using your phone.
     *
     * @param  \App\Mode\Phone  $phone
     * @return \App\User
     */
    protected function login_ussd_auto($phone)
    {
        $user = $phone->user()->first();
        Auth::guard('communication')->login($user);

        return $user;
    }

    /**
     * Process the registration of a number with a user.
     *
     * @param  string  $phoneNumber
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    protected function register_menu($phone_number, $text)
    {
        $response = null;
        $user_input = explode('*', $text, 2); // max 2 levels

        // level one (selecting login or register or display menu)
        switch ($user_input[0]) {
          case '1':
            // login...
            return $this->login_ussd($phone_number, $text);
            break;

          case '2':
            // register...
            $response = $this->register_ussd($phone_number, $text);
            break;

          default:
            // display menu
            $response_data  = "This phone number is not associated with any user account.\n";
            $response_data .= "Please select your action\n";
            $response_data .= "1. Login\n";
            $response_data .= "2. Register";

            $response = $this->server_response($response_data);
            break;
        }

        return $response;
    }

    /**
     * This is the ussd app's main menu.
     *
     * @param  string  $phoneNumber
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    protected function main_menu($user, $text)
    {
        $response = null;
        $user_input = explode('*', $text); // max 2 levels

        // home level of the main menu
        switch ($user_input[0]) {
          case '1':
            // my account option
            return $this->account_run();
            break;

          default:
            // display menu
            $response_data  = "1. My Account \n";
            $response_data .= "2. Help";

            $response = $this->server_response($response_data);
            break;
        }

        return $response;
    }
}
