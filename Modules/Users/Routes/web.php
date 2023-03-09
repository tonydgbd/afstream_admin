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
    Route::get('/users', 'UsersController@index')->name('users');
    Route::post('/store', 'UsersController@store')->name('store');
    Route::get('/edit/{id}', 'UsersController@editUser')->name('editUser');
    Route::post('/destroy/{id}', 'UsersController@destroy'); 
    Route::post('/updateUser/{id}', 'UsersController@updateUser')->name('updateUser'); 
    Route::patch('/updateStatus/{id}', 'UsersController@updateStatus');
    Route::post('/usersData', 'UsersController@usersData')->name('usersData');
    Route::delete('/bulkDelete', 'UsersController@bulkDelete')->name('bulkDelete');
    Route::get('/create', 'UsersController@create')->name('create');

});
