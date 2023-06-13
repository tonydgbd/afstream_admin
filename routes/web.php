<?php

use Illuminate\Support\Facades\Route;
use App\User;
use Modules\Album\Entities\Album;
use Modules\Setting\Entities\Currency;
use App\Notifications\UserNotification;
use App\Helpers\currencyRate;
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

Route::get('/changeCurrency/{id}', function ($id) {
    $currency = Currency::where('id', $id)->first();
    if (isset($currency)) {
        session()->put('currency', [
            'code' => $currency->code,
            'symbol' => $currency->symbol
        ]);
    }

    $rate = currencyRate::fetchRate();
    return session()->get('currency');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return "Config is cleared";
});
Route::get('/clear-view', function () {
    Artisan::call('view:clear');
    return "View is cleared";
});
Route::get('/clear-route', function () {
    Artisan::call('route:clear');
    return "Route is cleared";
});

Route::get('/console/schedule', function () {
    Artisan::call('schedule:run');
    return "Schedule is run";
});

Route::get('/currency-update', function () {
    Artisan::call('currency:update -o');
    return "All currencies are updated";
});

Route::get('terms', function () {
    return view('terms');
})->name('user.termsofuse');

Route::get('privacy_policy', function () {
    return view('privacy_policy');
})->name('user.privacy_policy');


Auth::routes(['login' => false, 'register' => false]);
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('login', 'Auth\LoginController@authenticated')->name('login.process');
Route::post('user/login/', 'AdminController@authenticated')->name('user.login'); // user login
Route::get('user_logout', 'AdminController@logout')->name('user.logout'); // user logout
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home_2', 'HomeController@home2')->name('home2');
Route::post('/user_register', 'AdminController@register')->name('user_register');
Route::get('/socialLogin/{service}', 'AdminController@socialLogin')->name('socialLogin');
Route::get('/callback/{service}', 'AdminController@socialLoginRedirect')->name('socialLoginRedirect');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');


Route::group(['middleware' => ['autheticated']], function () {

    Route::post('/paypal', 'TransactionController@postPaymentWithpaypal')->name('paypal');
    Route::get('/paypal', 'TransactionController@getPaymentStatus')->name('getPaymentStatus');
    Route::get('/checkout', 'TransactionController@paypalCancelReturn');

    Route::get('/stripe', 'TransactionController@stripe')->name('stripe');
    Route::post('/stripe/checkout', 'TransactionController@stripePayment')->name('stripe.checkout');

    Route::get('razorpay/payments', 'TransactionController@payWithRazorpay')->name('paywithrazorpay');
    Route::post('razorpay/payment', 'TransactionController@payment')->name('razor.payment');

    Route::get('/braintree/accesstoken', 'TransactionController@accesstoken')->name('bttoken');
    Route::get('/braintree', 'TransactionController@braintree')->name('braintree');
    Route::get('/payment/make', 'TransactionController@braintreePay')->name('payment.make');
    Route::post('/successBraintree', 'TransactionController@successBraintree')->name('successBraintree');

    Route::get('instamojo', 'TransactionController@instamojo');
    Route::post('paywithinstamojo', 'TransactionController@payWithIM');
    Route::get('pay-success', 'TransactionController@success')->name('instamojo.success');

    Route::get('/paystack', 'TransactionController@paystack');
    Route::post('/paywithPaystack', 'TransactionController@redirectToGateway')->name('paywithPaystack');
    Route::get('/paystack/callback', 'TransactionController@handleGatewayCallback');

    Route::get('/payu', 'TransactionController@payu');
    Route::get('/paywithpayu', 'TransactionController@payWithPayu')->name('payWithPayu');
    Route::get('/payUstatus', 'TransactionController@payUstatus')->name('payUstatus');

    Route::get('/paytm', 'TransactionController@order')->name('paytm');
    Route::post('/payment/status', 'TransactionController@paymentCallback');

    Route::post('/manual_pay', 'TransactionController@payWithManualPay')->name('payWithManualPay');

    Route::post('/user/comment/{type}/{id}', 'HomeController@user_comment');

    Route::get('/user/profile', 'HomeController@user_profile');
    Route::post('/update_profile', 'HomeController@update_profile');
    Route::get('/download_audio', 'HomeController@downloadaudio');

    Route::get('user/purchase/history', 'HomeController@purchaseHistory')->name('usersPurchaseHistory');
    Route::get('receipt/{id}', 'HomeController@audioPurchaseReceipt')->name('audioPurchaseReceipt');

    Route::get('user/download/history', 'HomeController@downloadHistory')->name('usersDownloadHistory');


    Route::post('/apply-coupon/{id}', 'TransactionController@applyCoupon')->name('applyCoupon');
    Route::post('/razorpay/proceed', 'TransactionController@razorpayFormRender')->name('razorpayFormRender');
    Route::post('/readNotification', 'HomeController@readNotification');
    Route::get('/payment-single/{id}', 'HomeController@paymentSingle');


    //Buy Artist Audio To Download
    Route::post('/razorpay/buySingleAudio', 'Artist\TransactionController@razorpayBuySingleAudioFormRender')->name('razorpayBuySingleAudioFormRender');
    Route::post('razorpay/singleaudio/payment', 'Artist\TransactionController@singleAudioPayment')->name('razor.singleaudio.payment');

    Route::post('/paypal/buySingleAudio', 'Artist\TransactionController@postPaymentWithpaypal')->name('paypal.buySingleAudio');
    Route::get('/paypal/paymentStatusSingleAudio', 'Artist\TransactionController@getPaymentStatus')->name('getPaymentStatus.buySingleAudio');
    Route::get('/checkout/buySingleAudio', 'Artist\TransactionController@paypalCancelReturn');

    Route::get('/stripe/buySingleAudio', 'Artist\TransactionController@stripe')->name('stripe.buySingleAudio');
    Route::post('/stripe/checkout/buySingleAudio', 'Artist\TransactionController@stripePayment')->name('stripe.checkout.buySingleAudio');

    Route::get('/paystack/buySingleAudio', 'Artist\TransactionController@paystack');
    Route::post('/paywithPaystack/buySingleAudio', 'Artist\TransactionController@redirectToGateway')->name('paywithPaystack.buySingleAudio');
    Route::post('/paystack/callback/buySingleAudio', 'Artist\TransactionController@handleGatewayCallback');

    Route::post('/deleteAccountPermanent', 'HomeController@deleteAccountPermanent')->name('deleteAccountPermanent');

    Route::post('/fetch_states', 'HomeController@fetch_states');
    Route::post('/fetch_city', 'HomeController@fetch_city');
});

Route::post('/add_to_favourite/{id}', 'HomeController@add_favourite_list')->name('album.add_favourite');
Route::post('/like_dislike_audio', 'HomeController@like_dislike_audio');

Route::get('/pricing-plan', 'HomeController@pricing_plan')->name('pricing-plan');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/admin', 'AdminController@index')->name('admin')->middleware('admin.auth');
    Route::get('locale/{locale}', 'AdminController@setLanguage');
    Route::post('dash_color', 'AdminController@getAdminDashColor')->name('dash.color');
    Route::post('/s3Audios', 'AdminController@s3Audios')->name('s3Audios');
    Route::get('/getImage', 'AdminController@getImage')->name('getImage');
    Route::get('user/invoice/{purchase_id}/{invoice_id}/{type}', 'AdminController@user_invoice')->name('user.invoice');
    Route::post('/payment_status', 'TransactionController@payment_status')->name('payment_status');
    Route::get('purchase/audio/{invoice_id}', 'AdminController@purchase_audio_invoice')->name('purchase.audio.invoice');
    Route::get('artist/payment/{invoice_id}', 'AdminController@artist_payment_invoice')->name('artist.payment.invoice');
    Route::get('paymentNotification/{data}', 'TransactionController@paymentNotify');
});

Route::group(['middleware' => ['artist.auth'], 'prefix' => 'artist'], function () {
    // Artist Dashboard And Profile
    Route::get('dashboard', 'Artist\ArtistController@index')->name('artist.home');
    Route::get('profile', 'Artist\ArtistController@profile')->name('artist.profile');
    Route::post('profile_update', 'Artist\ArtistController@profileUpdate')->name('artist.profile_update');
    // Artist Audio
    Route::get('audio/list', 'Artist\AudioController@index')->name('artist.audio');
    Route::post('audio/data', 'Artist\AudioController@audioData')->name('artist.audioData');
    Route::get('audio/create', 'Artist\AudioController@audioCreate')->name('artist.audio_create');
    Route::get('/audio/edit/{id}', 'Artist\AudioController@edit')->name('artist_audio.edit');
    Route::get('audio/edit/{id}', 'Artist\AudioController@audioEdit')->name('artist.audio_edit');
    Route::post('/audio/addEdit/{id}', 'Artist\AudioController@addEditAudio')->name('artistAddEditAudio');
    Route::post('/audio/langugage/artist', 'Artist\AudioController@getArtistRecordbylanguage')->name('artist.getArtistRecordbylanguage');
    Route::patch('/audio/status/{id}', 'Artist\AudioController@updateAudioStatus')->name('artist.updateAudioStatus');
    Route::post('/audio/destroy/{id}', 'Artist\AudioController@destroyAudio')->name('artist.destroyAudio');
    Route::delete('/bulkDeleteAudio', 'Artist\AudioController@bulkDeleteAudio')->name('artist.bulkDeleteAudio');

    // Artist Playlist
    Route::get('playlists', 'Artist\PlaylistController@index')->name('artist.playlist');
    Route::get('playlist/edit', 'Artist\PlaylistController@playlistEdit')->name('artist.playlist_edit');
    Route::post('playlist/update', 'Artist\PlaylistController@playlistUpdate')->name('artist.playlist_update');
    // Artist Integration        
    Route::get('integrations', 'Artist\ArtistController@artistIntegration')->name('artist.integrations');
    Route::post('save/integrations/{type}', 'Artist\ArtistController@saveArtistIntegrationData')->name('save.artist.integrations');
    Route::post('integration/change/status', 'Artist\ArtistController@changeIntegrationStatus')->name('artist.integration.changeStatus');
    // Artist Payment Gateway
    Route::get('/api', 'Artist\PaymentGatewayController@api')->name('artist.api');
    Route::post('/api_update/{type}', 'Artist\PaymentGatewayController@api_update')->name('artist.api.update');
    Route::post('/updateStatus', 'Artist\PaymentGatewayController@updateStatus')->name('artist.updateStatus');

    //Audio Transaction
    Route::get('/sales_history', 'Artist\AudioTransactionController@salesHistory')->name('artist.sales_history');
    Route::post('/sales_history_data', 'Artist\AudioTransactionController@salesHistoryData')->name('artist.sales_history_data');
    Route::get('/payment_history', 'Artist\AudioTransactionController@paymentHistory')->name('artist.payment_history');
    Route::post('/payment_history_data', 'Artist\AudioTransactionController@paymentHistoryData')->name('artist.payment_history_data');

    Route::get('/payment_request', 'Artist\AudioTransactionController@paymentRequest')->name('artist.request_payment');
    Route::post('/request_to_payment', 'Artist\AudioTransactionController@request_to_payment')->name('artist.request_to_payment');
});

//front url
Route::get('/', 'HomeController@index');
Route::post('/newsletter', 'HomeController@newsletter')->name('newsletter');
Route::post('/song_detail', 'HomeController@get_song_list');
Route::post('/songs', 'HomeController@play_single_music');
Route::post('/filter_language', 'HomeController@filter_music_language')->name('audio.language');
Route::get('/user/album', 'HomeController@album')->name('user.album');
Route::get('/album/single/{id}/{slug}', 'HomeController@album_single');
Route::get('/artist/single/{id}/{slug}', 'HomeController@artist_single')->name('artist.single');
Route::get('/audio/single/{id}/{slug}', 'HomeController@audio_single')->name('audio.single');
Route::get('/blog/single/{id}/{slug}', 'HomeController@blog_single')->name('blog.single');
Route::get('/blog/multiple/{id}', 'HomeController@blog_multiple')->name('blog.multiple');
Route::get('ytplaylist/single/{id}', 'HomeController@ytplaylist_single')->name('ytplaylist.single');


Route::get('/user/track', 'HomeController@audio')->name('user.audio');
Route::get('/user/favourite', 'HomeController@favourite')->name('user.favourite');
Route::get('/user/artist', 'HomeController@artist')->name('user.artist');
Route::get('/user/genres', 'HomeController@genres')->name('user.genres');
Route::get('/user/history', 'HomeController@history')->name('user.history');
Route::get('/user/download', 'HomeController@download')->name('user.download');
Route::get('/user/free-music', 'HomeController@free_music')->name('user.free_music');
Route::get('/user/playlist', 'HomeController@playlist')->name('user.playlist');

Route::post('/download_track', 'HomeController@download_track')->name('download_track');
Route::post('/download_artist_track', 'HomeController@download_artist_track')->name('download_artist_track');
Route::post('/download_list/{id}', 'HomeController@download_list')->name('download_list');

Route::post('/create_playlist', 'HomeController@create_playlist')->name('create_playlist');
Route::post('/play_playlist_song', 'HomeController@play_playlist_song');
Route::post('/add_in_playlist', 'HomeController@add_in_playlist');
Route::post('/remove_playlist', 'HomeController@remove_playlist');
Route::get('/playlist/single/{id}', 'HomeController@playlist_single');
Route::post('/playlist/remove_track', 'HomeController@remove_music_from_playlist');
Route::get('/user/radio_station', 'HomeController@radio_station')->name('user.radio_station');
Route::get('/radio/single/{id}/{slug}', 'HomeController@radio_single')->name('radio.single');
Route::get('/genre/single/{id}/{slug}', 'HomeController@genre_single')->name('genre.single');
Route::post('/song_play/count', 'HomeController@playSongCount');
Route::get('/set_top_detail', 'HomeController@top_detail_cron_job');

Route::get('/search/{name}', 'HomeController@search');
Route::get('/faqs', 'HomeController@faq');
Route::get('/blogs', 'HomeController@blog');
Route::get('/pages/{id}', 'HomeController@pages');

// Notification Route
Route::get('/notification/mark_read/{id}', 'HomeController@mark_read_notification');
Route::get('/notification/remove/{id}', 'HomeController@remove_notification');
Route::get('/notification/remove_all', 'HomeController@remove_all_notification');

// History Route
Route::get('/clear_history', 'HomeController@clear_all_history');

/*GUEST APIS*/

Route::post('login', 'api\v1\AuthController@login');
Route::post('register', 'api\v1\AuthController@register');
Route::post('forgot_password', 'api\v1\AuthController@forgotPassword');
Route::post('reset_password', 'api\v1\AuthController@resetPassword');

// App Information
Route::get('get_app_info', 'api\v1\GeneralController@getAppInfo');

Route::group(['middleware' => 'auth:api'], function () {

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
    Route::post('buy_audio_to_download', 'api\v1\ArtistAudioController@saveAudioPaymentData')->name('buy_audio_to_download');
    Route::post('remove_to_download_artist_track', 'api\v1\ArtistAudioController@remove_to_download_artist_track')->name('remove_to_download_artist_track');

    // Delete Account Permanent
    Route::post('/deleteAccountPermanent', 'api\v1\UserController@deleteAccountPermanent')->name('deleteAccountPermanent');

    // Get Blogs
    Route::get('get_blogs', 'api\v1\GeneralController@getBlogs');
    Route::post('get_blog_by_category_id', 'api\v1\GeneralController@getBlogByCategoryId');
    Route::post('get_single_blog', 'api\v1\GeneralController@getBlogById');

    // Get Users Audio Purchase Details
    Route::get('user_purchase_history', 'api\v1\UserController@getUserPurchaseHistory')->name('userPurchaseHistory');
    Route::get('receipt/{id}', 'api\v1\UserController@audioPurchaseReceipt')->name('audioPurchaseReceipt');
    Route::get('user/download/history', 'api\v1\UserController@downloadHistory')->name('usersDownloadHistory');
});
