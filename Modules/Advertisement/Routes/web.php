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
    Route::get('/advertisement', 'AdvertisementController@index')->name('adv');
    Route::post('/advData', 'AdvertisementController@advData')->name('advData');
    Route::get('/create/adv', 'AdvertisementController@createAdv')->name('adv.create');
    Route::post('/addEditAdv/{id}', 'AdvertisementController@addEditAdv')->name('addEditAdv');
    Route::get('/adv/edit/{id}', 'AdvertisementController@editAdv')->name('adv.edit');
    Route::patch('/adv/status/{id}', 'AdvertisementController@updateAdvStatus');
    Route::post('/adv/destroy/{id}', 'AdvertisementController@destroyAdv')->name('adv.destroy');
    Route::delete('/adv/bulk_delete', 'AdvertisementController@bulkDeleteAdvData')->name('adv.bulk_delete');
});
