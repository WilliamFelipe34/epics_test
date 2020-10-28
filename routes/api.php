<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function(){
    Route::resource('users', UserController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::get('/logs/{start}/{limit}', 'UserLogController@index');
    Route::get('/logs/{id}/{start}/{limit}', 'UserLogController@show');
});
