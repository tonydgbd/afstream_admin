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
    Route::get('/languages', 'LanguageController@languages')->name('languages');
    Route::post('/language_data', 'LanguageController@languageData')->name('languageData');
    Route::get('/language/create', 'LanguageController@createLLanguage')->name('language.create');
    Route::post('/language/addEdit/{id}', 'LanguageController@addEditLanguage')->name('addEditLanguage');
    Route::post('/language/data/{id}', 'LanguageController@getLanguageData')->name('getLanguageData');
    Route::patch('/language/status/{id}', 'LanguageController@updateLanguageStatus')->name('language.status');
    Route::post('/language/destroy/{id}', 'LanguageController@destroyLanguage');
    Route::delete('/language/bulk_delete', 'LanguageController@bulkDeleteLanguage')->name('language.bulk_delete');
});
