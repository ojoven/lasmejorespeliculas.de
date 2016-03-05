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

// Home
Route::get('/', 'IndexController@index');

// API calls
Route::controller('api', 'ApiController');

// HTML renders
Route::controller('html', 'HtmlController');

// We need this for external linking
Route::get('a/{name}', 'IndexController@actor');
Route::get('d/{name}', 'IndexController@director');
Route::get('s/{query}', 'IndexController@search');

// Screenshots
Route::get('p/{type}/{name}', 'IndexController@generateprofile');

// FilmDissafinityBot
Route::get('r/{review}', 'IndexController@review');
Route::get('randombadreview', 'IndexController@randombadreview');