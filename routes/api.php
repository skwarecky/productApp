<?php

use Illuminate\Http\Request;


    Route::get('/showAll', 'ProductController@showAll');
    //Showed all products without login
    Route::post('/register', 'RegisterController@register')->name('register');
    //register user
    Route::post('/login', 'LoginController@login')->name('login');
    //login user
    Route::get('/editedList', 'ProductController@showAllAdmin')->middleware('auth:api');
    //Loged user list, with access to edit, add and delete.
    Route::post('/edit/{id}', 'ProductController@edit')->middleware('auth:api');
    //Edit product
    Route::post('/add', 'ProductController@add')->middleware('auth:api');
    //Add product
    Route::delete('/delete/{id}', 'ProductController@delete')->middleware('auth:api');
    //Delete product
    Route::fallback(function () {
        return response()->json(['error' => 'Not Found!'], 404);
    });//Not founded route

