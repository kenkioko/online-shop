<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\Controller;
use App\Models\USSD;
use App\Models\Phone;
use App\Traits\USSD\USSDRegister;
use App\Traits\USSD\USSDAccount;
use App\Traits\USSD\USSDInput;
use App\Traits\USSD\USSDAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * This is the main ussd controller.
 * Other should inherit this parent class and call the function
 * `run(string $phoneNumber, string $text='')`.
 *
 * The structure (flow) of the ussd application
 * ============================================
 *
 *  [''] => if (phone is 'registered')
 *      [no] => auth_menu()
 *          [1] => login_ussd()
 *          [2] => register_ussd()
 *          [else] => exit()
 *
 *      [yes] => login_ussd_otp(input otp)
 *          [empty] => Otp::generate()
 *          [no] => exit()
 *          [yes] => main_menu(user_role)
 *              [seller] => show_seller_menu()
 *                  [1] => My account()
 *                  [2] => My Orders()
 *                  [3] => My Items()
 *
 *              [buyer] => show_buyer_menu()
 *                  [1] => My account()
 *                  [2] => Search Shop()
 *                  [3] => Search Item()
 *                  [4] => My Order()
 *
 */
class USSDController extends Controller
{
    use USSDInput, USSDAuth, USSDAccount;

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
     * @param array  $ussd_data
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    protected function run(array $ussd_data, string  $provider)
    {
        $response = null;
        $initialize = $this->initialize_ussd($ussd_data, $provider);

        // initialization failed
        if (! $initialize->status) {
          return $this->server_response($initialize->response, false, 500);
        }

        // check if phone number is associated with any user
        $phone = Phone::where('phone_number',$initialize->ussd->phoneNumber)->first();
        if ($phone) {
          // auto login first
          // $otp_login = $this->login_ussd_otp($phone, $text);
          // if ($otp_login->user and $otp_login->otp->status) {
          //   // show main menu
          //   $response = $this->main_menu($otp_login->user, $text);
          // } else if ($otp_login->otp->status) {
          //   // show newly generated otp token
          //   $response_data  = $otp_login->otp->message ."\n";
          //   $response = $this->server_response($response_data, false);
          // } else {
          //   // else error
          //   $response_data = $otp_login->otp->message;
          //   $response = $this->server_response($response_data, false);
          // }
        } else {
          $response = $this->auth_menu();
        }

        // default response
        if (! $response) {
          // invalid option
          dd('run', $this->get_active_ussd());

          $response_data  = "Invalid option. Session ended";
          return $this->server_response($response_data, false);
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
    private function auth_menu()
    {
        $response = null;
        $ussd = $this->get_active_ussd(true);

        // dd('auth_menu', $ussd->level_data);

        // level one (selecting login or register or display menu)
        switch ($ussd->level_data) {
          case '':
            // display menu
            $response_data  = "This phone number is not associated with any user account.\n";
            $response_data .= "Please select your action\n";
            $response_data .= "1. Login\n";
            $response_data .= "2. Register";

            return $this->server_response($response_data);

          case '1':
            // login...
            $this->ussd_level_up();
            return $this->login_ussd();

          case '2':
            // register...
            $this->ussd_level_up();
            return $this->register_ussd();
        }
    }

    // /**
    //  * This is the ussd app's main menu.
    //  * calls either the seller's or buyer's menu
    //  *
    //  * @param  \App\User  $user
    //  * @param  string  $text
    //  * @return \Illuminate\Http\Response
    //  */
    // private function main_menu($user, $text)
    // {
    //     $response = null;
    //     $user_input = explode('*', $text, 2); // max 2 levels
    //
    //     // home level of the main menu
    //     if ($user->hasRole('seller')) {
    //       // show the sellers main menu
    //       return $this->show_seller_menu($user, $text);
    //     }
    //     elseif ($user->hasRole('buyer')) {
    //       // show the buyers main menu
    //       return $this->show_buyer_menu($user->name, $text);
    //     }
    //     else {
    //       switch ($user_input[0]) {
    //         case '':
    //           // display menu
    //           $response_data  = "Welcome $user->name. \n";
    //           $response_data .= "\n";
    //           $response_data .= "1. My Account \n";
    //           return $this->server_response($response_data);
    //
    //         case '1':
    //           // my account option
    //           return $this->account_run();
    //
    //         default:
    //           // invalid option
    //           $response_data  = "Invalid option. Session ended";
    //           return $this->server_response($response_data, false);
    //         }
    //     }
    //
    // }
    //
    // /**
    //  * This is the ussd app seller's  menu
    //  *
    //  * @param  \App\User  $user
    //  * @param  string  $text
    //  * @return \Illuminate\Http\Response
    //  */
    // private function show_seller_menu($user, $text)
    // {
    //     $response = null;
    //     $user_input = explode('*', $text);
    //     $my_shop = $user->shop()->first();
    //
    //     switch ($user_input[0]) {
    //       case '':
    //         // display menu
    //         $response_data  = "Welcome $user->name. \n";
    //         $response_data .= "You have (" .$my_shop->getNewOrders(true). ") new orders\n";
    //         $response_data .= "\n";
    //         $response_data .= "1. My Account \n";
    //         $response_data .= "2. My Orders \n";
    //         $response_data .= "3. My Items \n";
    //
    //         return $this->server_response($response_data);
    //
    //       case '1':
    //         // my account option
    //         return $this->account_run();
    //
    //       default:
    //         // invalid option
    //         $response_data  = "Invalid option. Session ended";
    //         return $this->server_response($response_data, false);
    //     }
    // }
    //
    // /**
    //  * This is the ussd app buyers's  menu
    //  *
    //  * @param  string  $user_name
    //  * @param  string  $text
    //  * @return \Illuminate\Http\Response
    //  */
    // private function show_buyer_menu($user_name, $text)
    // {
    //     $response = null;
    //     $user_input = explode('*', $text);
    //
    //     // home level of the main menu
    //     switch ($user_input[0]) {
    //       case '':
    //         // display menu
    //         $response_data  = "Welcome $user_name \n";
    //         $response_data .= "\n";
    //         $response_data .= "1. My Account  \n";
    //         $response_data .= "2. Search Shop \n";
    //         $response_data .= "3. Search Item \n";
    //         $response_data .= "4. My Orders ";
    //         return $this->server_response($response_data);
    //
    //       case '1':
    //         // my account option
    //         return $this->account_run();
    //
    //       default:
    //         // invalid option
    //         $response_data  = "Invalid option. Session ended";
    //         return $this->server_response($response_data, false);
    //     }
    //
    // }
}
