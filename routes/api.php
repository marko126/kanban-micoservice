<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ticket routes

Route::get('tickets', 'TicketController@index');

Route::get('tickets/{id}', 'TicketController@show');

Route::post('tickets/create', 'TicketController@store');

Route::put('tickets/update/{id}', 'TicketController@update');

Route::delete('tickets/delete/{id}', 'TicketController@delete');

// User routes

Route::get('users', 'UserController@index');

Route::get('users/{id}', 'UserController@show');

Route::post('users/create', 'UserController@store');

Route::put('users/update/{id}', 'UserController@update');

Route::delete('users/delete/{id}', 'UserController@delete');

Route::post('register', 'Auth\RegisterController@register');