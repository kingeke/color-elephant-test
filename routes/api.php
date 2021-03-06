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

//health check endpoint

Route::get('/', function(){
    return messageResponse('success', 'Hello from API.');
});

Route::resource('transactions', 'TransactionController');

Route::resource('records', 'RecordController');