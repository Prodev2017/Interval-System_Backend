<?php

//Api routs. GET
Route::get('/api/pdfview',array('as'=>'pdfview','uses'=>'ReportController@pdfview'));
Route::get('/api/approveall',array('as'=>'approveall','uses'=>'ReportController@approveAll'));
Route::get('/api/approve', array('as'=>'approve','uses'=>'ReportController@approve'));

//Api routs. POST
Route::post('/api/updateuser', 'UserController@updateUsers');
Route::post('/api/report','ReportController@reportsStatus');
Route::post('/api/sendreminders','ReportController@sendReminders');
Route::post('/api/getselected','ManagerController@getSelectedUsers');
Route::post('/api/setselected','ManagerController@setSelectedUsers');