<?php
 
 
 
////////////////////  REST SERVICE /////////////////////////////////////////////

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () { 
	Route::post('login',   'APIController@login');
	Route::post('logout',  'APIController@logout');
	Route::post('refresh', 'APIController@refresh');	 
	Route::post('profile', 'APIController@profile');
	Route::post('history','APIController@history'); 
	Route::post('changepassword','APIController@changepassword'); 
	Route::post('deviceregister','APIController@deviceregister'); 
	Route::post('checkemail','APIController@checkemail'); 
	Route::post('codeverification','APIController@codeverification'); 
	Route::post('updatepassword','APIController@updatepassword');
     
});

/////////////////////////////////////////////////////////////////////////////////