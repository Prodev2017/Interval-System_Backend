<?php

Route::get('/api/pdfview',array('as'=>'pdfview','uses'=>'ReportController@pdfview'));
Route::get('/api/approveall',array('as'=>'approveall','uses'=>'ReportController@approveAll'));
Route::get('/api/approve', array('as'=>'approve','uses'=>'ReportController@approve'));

Route::middleware(['cors'])->group(function() {

    //Api routs. GET
    Route::get('/api/welcome', function () {
        return view('welcome');
    }); //TODO: this route should get laravel session and csrf token without welcome page


    Route::get('/api/timereport', 'ReportController@getTimeReport');

    //Api routs. POST
    Route::post('/api/updateuser', 'UserController@updateUsers');
    Route::post('/api/report', 'ReportController@reportsStatus');
    Route::post('/api/sendreminders', 'ReportController@sendReminders');
    Route::post('/api/getselected', 'ManagerController@getSelectedUsers');
    Route::post('/api/setselected', 'ManagerController@setSelectedUsers');
    Route::post('/api/timereport', 'ReportController@setTimeReport');
});