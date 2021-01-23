<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;

class OTPController extends Controller
{
    /**
     * Sends OTP to the specified phone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(Request $request)
    {
        return response()->json([
          'status' => true,          
          'phone' => $request->all(),
          'message' => 'function not set',
        ]);
    }
}
