<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\USSD\USSDController as Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\USSD;

class AfricastkngController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
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
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
          $response = "An error ocurred while processing the data.\n";
          $response .= $this->getValidationErrors($validator);
          return $this->server_response($response, false, 500);
        }

        $validated = $validator->validate();
        $response =  DB::transaction(function () use ($validated) {
          // save USSD session to db
          $ussd = new USSD($validated);
          $ussd->sessionId = $validated["sessionId"];
          $ussd->provider = 'africastkng';
          $ussd->save();

          // process the response
          return $this->run($validated["phoneNumber"], isset($validated["text"]) ? $validated["text"] : null);
        });

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function show(USSD $ussd)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\USSD  $ussd
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
     * @param  \App\Models\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, USSD $ussd)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\USSD  $ussd
     * @return \Illuminate\Http\Response
     */
    public function destroy(USSD $ussd)
    {
        abort(404);
    }
}
