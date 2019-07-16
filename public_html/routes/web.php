<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout',function(){
	Auth::logout();
	return redirect("home");
});

Route::group(array("prefix"=>"admin","middleware"=>"auth"),function(){


});