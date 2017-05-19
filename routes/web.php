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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/me', 'IntervalController@getMe');
Route::get('/getpersone/{id}', 'IntervalController@getPersone');
Route::get('/getclientall/{id}', 'IntervalController@getClient');
Route::get('/getpersonecontact/{id}', 'IntervalController@getPersoneEmail');
Route::get('/getpersone/', 'IntervalController@getPersone');
Route::get('/getclientall', 'IntervalController@getClient');
Route::get('/getproject/{id}', 'IntervalController@getProject');
Route::get('/getproject', 'IntervalController@getProject');
Route::get('/getprojectmodule/{id}', 'IntervalController@getProjectModule');
Route::get('/getprojectmodule', 'IntervalController@getProjectModule');
Route::get('/gettask/{id}', 'IntervalController@getTask');
Route::get('/gettask', 'IntervalController@getTask');
Route::get('/gettime/{timestart}/{timeend}', 'IntervalController@getTime');
Route::get('/gettime', 'IntervalController@getTime');
Route::get('/getworktype', 'IntervalController@getWorkType');