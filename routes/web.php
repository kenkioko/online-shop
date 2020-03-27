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
Auth::routes();

// index page
Route::get('/', 'HomeController@index')->name('home.index');
Route::redirect('/home', '/');
Route::redirect('/index', '/');

// user profile
Route::resource('profile', 'ProfileController')->only([
  'index', 'update'
])->middleware(['auth']);

// available resource controllers for website
Route::resources([
  'categories' => 'CategoryController',
  // 'items' => 'ItemController',
  // 'orders' => 'OrderController',
  // 'cart' => 'CartController',
]);

Route::namespace('Web')->group(function () {
  Route::resource('items', 'ItemController')->only([
    'show'
  ]);
  Route::resource('orders', 'OrderController')->only([
    'index', 'store', 'show'
  ]);
  Route::resource('cart', 'CartController')->except([
    'create', 'show', 'edit'
  ]);

});

//admin routes
Route::middleware(['auth', 'permission:dashboard.view'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    // admin dashboard
    Route::redirect('/', '/admin/dashboard');
    Route::get('dashboard', 'DashboardController@index')->name('dash');

    // available resource controllers for admin dashboard
    Route::resources([
      'categories' => 'CategoryController',
      // 'items' => 'ItemController',
      // 'orders' => 'OrderController',
      'users' => 'UserController',
    ]);

    Route::namespace('Dash')->group(function () {
      Route::resource('items', 'ItemController')->except([
        'show',
      ]);
      Route::resource('orders', 'OrderController')->except([
        'create', 'store', 'edit'
      ]);

    });
});
