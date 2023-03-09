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




Route::group(['middleware'=>['auth','admin.auth']], function(){

    Route::get('/audio_languages', 'AudioLanguageController@audioLanguages')->name('audio_languages');

    Route::post('/audio_language_data', 'AudioLanguageController@audioLanguageData')->name('audioLanguageData');

    Route::post('/audio_language/data/{id}', 'AudioLanguageController@getLanguageData')->name('audioGetLanguageData');

    Route::get('/audio_language/create', 'AudioLanguageController@createLLanguage')->name('audio_language.create');

    Route::post('/audio_language/addEdit/{id}', 'AudioLanguageController@addEditLanguage')->name('addEditAudioLanguage');

    Route::patch('/audio_language/status/{id}', 'AudioLanguageController@updateLanguageStatus')->name('audio_language.status');

    Route::post('/audio_language/destroy/{id}', 'AudioLanguageController@destroyLanguage');

    Route::delete('/audio_language/bulk_delete', 'AudioLanguageController@bulkDeleteLanguage')->name('audio_language.bulk_delete');

});