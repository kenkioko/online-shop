<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| USSD Routes
|--------------------------------------------------------------------------
|
| The routes to respond to Unstructured Supplementary Service Data (USSD).
|
*/

Route::namespace('USSD')->group(function () {
  Route::resource('africastkng', 'AfricastkngController')->only([
    'store'
  ]);
});
