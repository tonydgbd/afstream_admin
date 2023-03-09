<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    /*GUEST APIS*/
    
    Route::post('login', 'api\v1\AuthController@login');
    Route::post('register', 'api\v1\AuthController@register');
    Route::post('forgot_password', 'api\v1\AuthController@forgotPassword');
    Route::post('reset_password', 'api\v1\AuthController@resetPassword');
    
    // App Information
    Route::get('get_app_info', 'api\v1\GeneralController@getAppInfo'); 

Route::group(['middleware' => 'auth:api'], function(){
    
    Route::post('logout', 'api\v1\AuthController@logout');
    
    // User Detail & Update profile
    Route::get('get_user_setting_details', 'api\v1\UserController@userDetails');
    Route::post('updateProfile', 'api\v1\UserController@updateProfileDetail');
    
    // Music Language
    Route::get('musicLanguages', 'api\v1\AudioController@getMusiclanguages');
    Route::post('setMusicLanguages', 'api\v1\AudioController@setMusicLanguages');
    
    // Music Category
    Route::get('musicCategories', 'api\v1\AudioController@getMusicCategories');
    Route::post('getMusicByCategory', 'api\v1\AudioController@getMusicByCategoryId');
    Route::post('getMusic', 'api\v1\AudioController@getMusicAll');
    // Search Music
    Route::post('searchMusic', 'api\v1\AudioController@searchMusic');
    
    // Favourite List
    Route::post('addFavouriteList', 'api\v1\AudioController@addFavouriteList');
    Route::post('favouriteList', 'api\v1\AudioController@favouriteList');
    
    // Play List    
    Route::get('playlist', 'api\v1\AudioController@playlist');
    Route::post('create_playlist', 'api\v1\AudioController@create_playlist');
    Route::post('delete_playlist', 'api\v1\AudioController@delete_playlist');
    Route::post('update_playlist_name', 'api\v1\AudioController@update_playlist');
    Route::post('add_playlist_music', 'api\v1\AudioController@add_playlist_music');
    Route::post('remove_playlist_music', 'api\v1\AudioController@remove_playlist_music');
    
    // Plans
    Route::get('plan_list', 'api\v1\AudioController@plan_list');
    
    // History
    Route::get('music_history', 'api\v1\AudioController@music_history');
    Route::post('addremove_musichistory', 'api\v1\AudioController@addremove_musichistory');
    
    // Download Music
    Route::get('downloaded_music_list', 'api\v1\AudioController@downloaded_music_list');
    Route::post('addremove_downloadmusic', 'api\v1\AudioController@addremove_downloadmusic');
    
    // Coupon Management
    Route::get('get_coupon_list', 'api\v1\AudioController@get_user_coupon_list');   
    Route::post('user_coupon_code', 'api\v1\AudioController@user_coupon_detail');  
    
    // Payment Transaction
    Route::post('save_payment_transaction', 'api\v1\TransactionController@savePaymentData'); 

    // Youtube Paylists
    Route::get('yt_pLaylists', 'api\v1\AudioController@ytPlaylists'); 

    // Artist Audio transaction and downloads
    Route::post('buy_audio_to_download','api\v1\ArtistAudioController@saveAudioPaymentData')->name('buy_audio_to_download');  
    Route::post('remove_to_download_artist_track','api\v1\ArtistAudioController@remove_to_download_artist_track')->name('remove_to_download_artist_track');  
    
    // Delete Account Permanent
    Route::post('/deleteAccountPermanent','api\v1\UserController@deleteAccountPermanent')->name('deleteAccountPermanent');
    
    // Get Blogs
    Route::get('get_blogs','api\v1\GeneralController@getBlogs');
    Route::post('get_blog_by_category_id','api\v1\GeneralController@getBlogByCategoryId');
    Route::post('get_single_blog','api\v1\GeneralController@getBlogById');
    
    // Get Users Audio Purchase Details
    Route::get('user_purchase_history','api\v1\UserController@getUserPurchaseHistory')->name('userPurchaseHistory');
    Route::get('receipt/{id}','api\v1\UserController@audioPurchaseReceipt')->name('audioPurchaseReceipt');
    Route::get('user/download/history','api\v1\UserController@downloadHistory')->name('usersDownloadHistory');
});