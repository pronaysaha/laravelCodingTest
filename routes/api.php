<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/users', 'UserController@store');
Route::post('/login', 'AuthController@login');

Route::middleware(['auth'])->group(function() {
    Route::get('/', 'TransactionController@index');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/deposit', 'TransactionController@showDeposits');
});

Route::middleware(['auth'])->group(function() {
    Route::post('/deposit', 'TransactionController@deposit');
});


Route::middleware(['auth'])->group(function() {
    Route::get('/withdrawal', 'TransactionController@showWithdrawals');
});


Route::middleware(['auth'])->group(function() {
    Route::post('/withdrawal', 'TransactionController@withdraw');
});






