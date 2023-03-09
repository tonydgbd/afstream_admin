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
    Route::get('/artist', 'ArtistController@index')->name('artist');
    Route::post('/artist_data', 'ArtistController@artistData')->name('artistData');
    Route::get('/artist/create', 'ArtistController@createArtist')->name('artist.create');
    Route::get('/artist/edit/{id}', 'ArtistController@editArtist')->name('artist.edit');
    Route::post('/addEditArtist/{id}', 'ArtistController@addEditArtist')->name('addEditArtist');
    Route::post('/artist/destroy/{id}', 'ArtistController@destroyArtist');
    Route::patch('/artist/status/{id}', 'ArtistController@updateArtistStatus');
    Route::patch('/artist/varify_status/{id}', 'ArtistController@updateArtistVerifyStatus');
    Route::delete('/artist/bulk_delete', 'ArtistController@bulkDeleteArtistData')->name('artist.bulk_delete');

    Route::get('/artist/genre', 'ArtistController@artistGenres')->name('artist.genre');
    Route::post('/artist_genre_data', 'ArtistController@artistGenreData')->name('artistGenreData');
    Route::get('/artist/genre/create', 'ArtistController@createArtistGenre')->name('artist.genre.create');
    Route::post('/artist/genre/addEdit/{id}', 'ArtistController@addEditArtistGenre')->name('addEditArtistGenre');
    Route::post('/genre/data/{id}', 'ArtistController@getArtistGenreData')->name('getArtistGenreData');
    Route::get('/artist/genre/edit/{id}', 'ArtistController@editArtistGenre')->name('artist.genre.edit');
    Route::post('/artist/genre/destroy/{id}', 'ArtistController@destroyArtistGenre');
    Route::patch('/artist/genre/status/{id}', 'ArtistController@updateArtistGenreStatus');
    Route::delete('/artist/genre/bulk_delete', 'ArtistController@bulkDeleteArtistGenre')->name('artist.genre.bulk_delete');
    
});
