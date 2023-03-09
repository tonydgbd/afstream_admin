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
    Route::get('/audio', 'AudioController@index')->name('audio');
    Route::get('/audio/create', 'AudioController@create')->name('audio.create');
    Route::post('/audio/addEdit/{id}', 'AudioController@addEditAudio')->name('addEditAudio');
    Route::get('/audio/edit/{id}', 'AudioController@edit')->name('audio.edit');
    Route::post('/audioData', 'AudioController@audioData')->name('audioData');
    Route::patch('/audio/status/{id}', 'AudioController@updateAudioStatus')->name('updateAudioStatus');
    Route::post('/audio/destroy/{id}', 'AudioController@destroyAudio')->name('destroyAudio');
    Route::delete('/bulkDeleteAudio', 'AudioController@bulkDeleteAudio')->name('bulkDeleteAudio');

    Route::post('/audio/langugage/artist', 'AudioController@getArtistRecordbylanguage')->name('getArtistRecordbylanguage');

    Route::get('/audio_genres', 'AudioController@audioGenres')->name('audio_genres');
    Route::post('/audio_genres/{id}', 'AudioController@addEditAudioGenre')->name('genres');
    Route::post('/audio_genre_data', 'AudioController@showAudioGenreData')->name('showAudioGenreData');
    Route::get('/getAudioGenreData/{id}', 'AudioController@getAudioGenreData')->name('getAudioGenreData');
    Route::patch('/updateAudioGenre/{id}', 'AudioController@updateAudioGenre')->name('updateAudioGenre');
    Route::post('/destroyAudioGenre/{id}', 'AudioController@destroyAudioGenre')->name('destroyAudioGenre');
    Route::delete('/bulkDeleteAudioGenre', 'AudioController@bulkDeleteAudioGenre')->name('bulkDeleteAudioGenre');

    Route::get('/audio_player', 'AudioController@audio_player');
});
