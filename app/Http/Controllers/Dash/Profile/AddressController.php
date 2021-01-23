<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        return DB::transaction(function () use ($address) {
            $default = Address::where([
                'user_id' => Auth::id(),
                'primary_address' => true,
            ])->first();

            // remove default address
            if ($default) {
                $default->primary_address = false;
                $default->save();
            }

            // set the new default address
            $address->primary_address = true;
            $address->save();

            return back()->with('success', 'The default address was changed successfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        return DB::transaction(function () use ($address) {
            // delete the address
            $address->delete();                    

            // select another primary address
            if ($address->primary_address) {
                $user_address = Address::where('user_id', Auth::id())->first();
                if ($user_address) {
                    $user_address->primary_address = true;
                    $user_addresses->save();
                }
            }
            
            return back()->with('success', 'The address was removed successfully');
        });    
    }
}
