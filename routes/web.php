<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// auth routes from laravel/ui
Auth::routes(['verify' => true]);

// index page
Route::redirect('/home', '/');
Route::redirect('/index', '/');

// available resource controllers for website
Route::namespace('Web')->group(function () {
  Route::get('/', 'HomeController@index')->name('home.index');
  Route::resource('shops', 'ShopController')->only([
    'show'
  ]);

  Route::resource('categories', 'CategoryController')->only([
    'index', 'show'
  ]);

  Route::get('items/products', 'ItemController@indexProduct')
    ->name('items.index_products');

  Route::get('items/services', 'ItemController@indexService')
    ->name('items.index_services');
    
  Route::resource('items', 'ItemController')->only([
    'show'
  ]);

  Route::post('orders/bid', 'OrderController@storeBid')
    ->name('orders.bid');
    
  Route::resource('orders', 'OrderController')->only([
    'index', 'store', 'show'
  ]);

  Route::resource('cart', 'CartController')->except([
    'create', 'show', 'edit'
  ]);

});

// admin dashboard routes
Route::middleware(['auth', 'permission:dashboard.view'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
      // admin dashboard
      Route::redirect('/', '/admin/dashboard');
      // available resource controllers for admin dashboard
      Route::namespace('Dash')->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dash');
        Route::resources([
          'categories' => 'CategoryController',
          'users' => 'UserController',
        ]);

        Route::resource('items', 'ItemController')->except([
          'show',
        ]);

        Route::resource('orders', 'OrderController')->except([
          'create', 'store', 'edit'
        ]);

      });
    });


// user profile
Route::middleware('auth')
    ->namespace('Profile')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
      Route::resource('', 'ProfileController')->only([
        'index', 'store'
      ]);

      Route::resource('phone', 'PhoneController')->only([
        'update', 'destroy'
      ]);

      Route::resource('address', 'AddressController')->only([
        'update', 'destroy'
      ]);
    });


// Ajax routes
Route::namespace('Ajax')
    ->prefix('ajax')
    ->name('ajax.')
    ->group(function () {
      Route::post('otp', 'OTPController@sendOTP')->name('otp.post');
    });

// clear cache route
Route::get('/clear', function() {
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');

  return "Cleared!";
});
