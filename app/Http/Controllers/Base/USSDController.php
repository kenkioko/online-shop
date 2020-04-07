<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Model\USSD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class USSDController extends Controller
{
    //
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
     * @return \Illuminate\Http\Response
     */
    protected function server_response($response, $code=200)
    {
        // Echo the response back to the API
        return response($response, $code)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * This is the starting point for the ussd processing.
     *
     * @param  string  $response
     * @return \Illuminate\Http\Response
     */
    protected function run($phoneNumber, $text='')
    {
        $response = 'END Session Ended';

        if ($text == "") {
            // This is the first request. Note how we start the response with CON
            $response  = "CON What would you want to check \n";
            $response .= "1. My Account \n";
            $response .= "2. My phone number";

        } else if ($text == "1") {
            // Business logic for first level response
            $response = "CON Choose account information you want to view \n";
            $response .= "1. Account number \n";
            $response .= "2. Account balance";

        } else if ($text == "2") {
            // Business logic for first level response
            // This is a terminal request. Note how we start the response with END
            $response = "END Your phone number is ".$phoneNumber;

        } else if($text == "1*1") {
            // This is a second level response where the user selected 1 in the first instance
            $accountNumber  = "ACC1001";

            // This is a terminal request. Note how we start the response with END
            $response = "END Your account number is ".$accountNumber;

        } else if ( $text == "1*2" ) {
            // This is a second level response where the user selected 1 in the first instance
            $balance  = "KES 10,000";

            // This is a terminal request. Note how we start the response with END
            $response = "END Your balance is ".$balance;
        }

        return $response;
    }
}
