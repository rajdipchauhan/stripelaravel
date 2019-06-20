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

Route::get('/', function () {
    return view('welcome');
});
// Make Payment Route

Route::get('makepayment', 'Controller@MakePayment');
Route::get('storedcardpayment', 'Controller@StoredCardPayment');
Route::get('transactions', 'Controller@TransactionsLog');
Route::get('cardlist', 'Controller@CardList');


Route::get('GetCustomers', 'Controller@GetCustomers');
Route::get('GetCustomersbyid', 'Controller@GetCustomersById');
Route::post('storepayment', 'Controller@SaveDirectPayment');
Route::post('cardstorepayment', 'Controller@SaveCardPayment');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
