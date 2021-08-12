<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::resource("question","MasterController");
Route::get('question/{id}/language',"MasterController@filterLang");
Route::get('question/{id}/level',"MasterController@filterLevel");
Route::post('question/validate',"MasterController@qvalidate");
