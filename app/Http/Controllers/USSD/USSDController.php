<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\Controller;
use App\Models\USSD;
use App\Models\Phone;
use App\Traits\USSD\USSDRegister;
use App\Traits\USSD\USSDAccount;
use App\Traits\USSD\USSDAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class USSDController extends Controller
{
    use USSDAuth, USSDAccount;

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
          $otp_login = $this->login_ussd_otp($phone, $text);
          if ($otp_login->user and $otp_login->otp->status) {
            // show main menu
            $response = $this->main_menu($otp_login->user, $text);
          } else if ($otp_login->otp->status) {
            // show newly generated otp token
            $response_data  = $otp_login->otp->message ."\n";
            $response_data .= $otp_login->otp->token;
            $response = $this->server_response($response_data, false);
          } else {
            // else error
            $response_data = $otp_login->otp->message;
            $response = $this->server_response($response_data, false);
          }
        } else {
          $response = $this->register_menu($phoneNumber, $text);
        }

        // default response
        if (! $response) {
          $response = $this->server_response('Error Session Ended', false);
        }
        return $response;
    }

    /**
     * Process the registration of a number with a user.
     *
     * @param  string  $phoneNumber
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    private function register_menu($phone_number, $text)
    {
        $response = null;
        $user_input = explode('*', $text, 2); // max 2 levels

        // level one (selecting login or register or display menu)
        switch ($user_input[0]) {
          case '1':
            // login...
            return $this->login_ussd($phone_number, $text);

          case '2':
            // register...
            return $this->register_ussd($phone_number, $text);

          default:
            // display menu
            $response_data  = "This phone number is not associated with any user account.\n";
            $response_data .= "Please select your action\n";
            $response_data .= "1. Login\n";
            $response_data .= "2. Register";

            return $this->server_response($response_data);
        }
    }

    /**
     * This is the ussd app's main menu.
     * calls either the seller's or buyer's menu
     *
     * @param  \App\User  $user
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    private function main_menu($user, $text)
    {
        $response = null;
        $user_input = explode('*', $text, 2); // max 2 levels

        // home level of the main menu
        if ($user->hasRole('seller')) {
          // show the sellers main menu
          return $this->show_seller_menu($user, $text);
        }
        elseif ($user->hasRole('buyer')) {
          // show the buyers main menu
          return $this->show_buyer_menu($user->name, $text);
        }
        else {
          switch ($user_input[0]) {
            case '1':
              // my account option
              return $this->account_run();
              break;

            default:
              // display menu
              $response_data  = "Welcome $user->name. \n";
              $response_data .= "\n";
              $response_data .= "1. My Account \n";
              return $this->server_response($response_data);
            }
        }

    }

    /**
     * This is the ussd app seller's  menu
     *
     * @param  \App\User  $user
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    private function show_seller_menu($user, $text)
    {
        $response = null;
        $user_input = explode('*', $text);
        $my_shop = $user->shop()->first();

        switch ($user_input[0]) {
          case '1':
            // my account option
            return $this->account_run();
            break;

          default:
            // display menu
            $response_data  = "Welcome $user->name. \n";
            $response_data .= "You have (" .$my_shop->getNewOrders(true). ") new orders\n";
            $response_data .= "\n";
            $response_data .= "1. My Account \n";
            $response_data .= "2. My Orders \n";
            $response_data .= "3. My Items \n";

            return $this->server_response($response_data);
        }
    }

    /**
     * This is the ussd app buyers's  menu
     *
     * @param  string  $user_name
     * @param  string  $text
     * @return \Illuminate\Http\Response
     */
    private function show_buyer_menu($user_name, $text)
    {
        // code...
        $response = null;
        $user_input = explode('*', $text);

        // home level of the main menu
        switch ($user_input[0]) {
          case '1':
            // my account option
            return $this->account_run();

          default:
            // display menu
            $response_data  = "Welcome $user_name \n";
            $response_data .= "\n";
            $response_data .= "1. My Account  \n";
            $response_data .= "3. Search Shop \n";
            $response_data .= "4. Search Item \n";
            $response_data .= "2. My Orders ";

            return $this->server_response($response_data);
        }

    }
}
