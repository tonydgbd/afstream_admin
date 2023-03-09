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

// Route::prefix('adminplaylist')->group(function() {
//     Route::get('/', 'AdminPlaylistController@index');
// });


Route::group(['middleware'=>['auth','admin.auth']],function (){ 
    Route::get('/admin/playlist', 'AdminPlaylistController@index')->name('admin.playlist');
    Route::get('/admin/playlist/create', 'AdminPlaylistController@create')->name('admin.playlist.create');
    Route::post('/admin/playlist/addEdit/{id}', 'AdminPlaylistController@addEditPlaylist')->name('addEditAdminPlaylist');
    Route::get('/admin/playlist/edit/{id}', 'AdminPlaylistController@edit')->name('admin.playlist.edit');
    Route::post('/admin/playlistData', 'AdminPlaylistController@playlistData')->name('adminPlaylistData');
    Route::patch('/admin/playlist/status/{id}', 'AdminPlaylistController@updatePlaylistStatus')->name('updateAdminPlaylistStatus');
    Route::post('/admin/playlist/destroy/{id}', 'AdminPlaylistController@destroyPlaylist')->name('destroyAdminPlaylist');
    Route::delete('/admin/bulkDeletePlaylist', 'AdminPlaylistController@bulkDeletePlaylist')->name('bulkDeleteAdminPlaylist');
    
    Route::get('/admin/getRecordbylanguage/{id}', 'AdminPlaylistController@getRecordbylanguage')->name('getRecordbylanguage');


    Route::get('/playlist_genres', 'AdminPlaylistController@playlistGenres')->name('playlist_genres');
    Route::post('/playlist_genre_data', 'AdminPlaylistController@showPlaylistGenreData')->name('showPlaylistGenreData');
    Route::post('/playlist_genres/{id}', 'AdminPlaylistController@addEditPlaylistGenre')->name('addEditPlaylistGenre'); 
    Route::get('/getPlaylistGenreData/{id}', 'AdminPlaylistController@getPlaylistGenreData')->name('getPlaylistGenreData');
    Route::patch('/updatePlaylistGenre/{id}', 'AdminPlaylistController@updatePlaylistGenre')->name('updatePlaylistGenre');
    Route::post('/destroyPlaylistGenre/{id}', 'AdminPlaylistController@destroyPlaylistGenre')->name('destroyPlaylistGenre');
    Route::delete('/bulkDeletePlaylistGenre', 'AdminPlaylistController@bulkDeletePlaylistGenre')->name('bulkDeletePlaylistGenre');

});