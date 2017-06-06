<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

//Route::get('/login', 'UserController@login');

Route::post('/logout', function (Request $request){
    Auth::logout(); return true;
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
