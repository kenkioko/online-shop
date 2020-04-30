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
        $ussd_data = $request->all();
        $response =  DB::transaction(function () use ($ussd_data) {
          // process the response
          return $this->run($ussd_data, 'africastkng');
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
