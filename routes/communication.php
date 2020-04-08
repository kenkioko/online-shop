<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Routes For Comunication Services
|--------------------------------------------------------------------------
|
| The routes to respond to Unstructured Supplementary Service Data (USSD).
|
*/

// ussd services
Route::prefix('ussd')->namespace('USSD')->group(function () {
  Route::resource('africastkng', 'AfricastkngController')->only([
    'store'
  ]);
});


// sms services
// Route::prefix('sms')->group(function () {
// 
// });
