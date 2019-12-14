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

// available resources
Route::resources([
  'category' => 'CategoryController',
  'item' => 'ItemController',
  'user' => 'UserController',
]);

//auth routes from laravel/ui
Auth::routes();

//index page
Route::get('/', 'HomeController@index')->name('home.index');
Route::redirect('/home', '/');
Route::redirect('/index', '/');

//admin dashboard
Route::middleware(['auth', 'admin'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('dashboard', 'DashboardController@index')->name('dash');
});
