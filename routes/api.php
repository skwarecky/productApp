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

    Route::get('/showAll', 'ProductController@showAll');
    Route::post('/register', 'RegisterController@register')->name('register');
    Route::post('/login', 'LoginController@login')->name('login');
    
    Route::get('/editedList', 'ProductController@showAllAdmin')->middleware('auth:api');
    Route::post('/edit/{id}', 'ProductController@edit')->middleware('auth:api');
    Route::post('/add', 'ProductController@add')->middleware('auth:api');
    Route::delete('/delete/{id}', 'ProductController@delete')->middleware('auth:api');
    Route::fallback(function () {
        return response()->json(['error' => 'Not Found!'], 404);
    });

