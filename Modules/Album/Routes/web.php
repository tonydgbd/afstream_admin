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
    Route::get('/album', 'AlbumController@index')->name('album');
    Route::post('/albums_data', 'AlbumController@albumData')->name('albumData');
    Route::get('/albums/create', 'AlbumController@createAlbum')->name('album.create');
    Route::post('/addEditAlbum/{id}', 'AlbumController@addEditAlbum')->name('addEditAlbum');
    Route::get('/albums/edit/{id}', 'AlbumController@editAlbum')->name('album.edit');
    Route::post('/albums/destroy/{id}', 'AlbumController@destroyAlbum');
    Route::patch('/albums/status/{id}', 'AlbumController@updateAlbumStatus');
    Route::delete('/albums/bulk_delete', 'AlbumController@bulkDeleteAlbumData')->name('albums.bulk_delete');
    
    Route::post('/audio/langugage/album', 'AlbumController@getAlbumRecordbylanguage')->name('getAlbumRecordbylanguage');
});
