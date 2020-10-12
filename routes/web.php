<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('shop', 'BitPayController@index');
Route::post('pay', 'BitPayController@pay')->name('pay');
// Route::post('handle_webhooks', 'BitPayController@handle_webhooks');
Route::get('bitpay-redirect-back', 'BitPayController@handleResponse')->name('bitpay-redirect-back');


