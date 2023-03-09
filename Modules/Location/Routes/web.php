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

Route::group(['middleware'=>['auth','admin.auth']],function (){
    Route::get('/country', 'LocationController@index')->name('country');
    Route::get('/state', 'LocationController@state')->name('state');
    Route::post('/stateData', 'LocationController@stateData')->name('stateData');
    Route::post('/getStateName/{id}','LocationController@getStateName');
    Route::post('/saveState/{id}', 'LocationController@saveState')->name('saveState');

    Route::get('/city', 'LocationController@city')->name('city');
    Route::post('/cityData', 'LocationController@cityData')->name('cityData');
    Route::post('/getCityName/{id}','LocationController@getCityName');
    Route::post('/saveCity/{id}', 'LocationController@saveCity')->name('saveCity');

    Route::post('/locationData', 'LocationController@locationData')->name('locationData');
    Route::post('/saveCountry/{id}', 'LocationController@saveCountry')->name('saveCountry');
    Route::post('/getCountryName/{id}','LocationController@getCountryName');
    Route::post('/destroyCountry/{id}','LocationController@destroyCountry');
    Route::delete('/bulkDeleteCountry','LocationController@bulkDeleteCountry')->name('bulkDeleteCountry');
});
