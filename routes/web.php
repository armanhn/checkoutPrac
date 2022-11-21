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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/product/index', 'App\Http\Controllers\ProductController@index')->name('product.index');
Route::post('/product/index/enrollment', 'App\Http\Controllers\ProductController@collect_data')->name('product.data');
Route::post('/product/index/checkout', 'App\Http\Controllers\ProductController@checkout')->name('product.checkout');
Route::get('/product/index/success', 'App\Http\Controllers\ProductController@success')->name('success');
Route::get('/product/index/cancel', 'App\Http\Controllers\ProductController@cancel')->name('cancel');
Route::post('/product/webhook', 'App\Http\Controllers\ProductController@webhook')->name('webhook');
