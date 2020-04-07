<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\Base\USSDController as Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\USSD;

class AfricastkngController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ussd_test');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * Reads the variables sent via POST from Africastkng gateway.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate_data($request->all());
        $response =  DB::transaction(function () use ($validated) {
          $sessionId   = $validated["sessionId"];
          $serviceCode = $validated["serviceCode"];
          $phoneNumber = $validated["phoneNumber"];
          $networkCode = $validated["networkCode"];
          $text        = $validated["text"];

          // save USSD session to db
          $ussd = new USSD($validated);
          $ussd->sessionId = $sessionId;
          $ussd->provider = 'africastkng';
          $ussd->save();

          // process the response
          $response = $this->run($sessionId, $serviceCode, $phoneNumber, $networkCode, $text);
          // dd($validated, $response, $ussd);
          return $response;
        });

        return $this->server_response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function show(USSD $ussd)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function edit(USSD $ussd)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, USSD $ussd)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function destroy(USSD $ussd)
    {
        abort(404);
    }
}
