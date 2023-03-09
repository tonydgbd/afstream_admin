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


Route::group(['middleware' => ['auth','admin.auth']], function(){    
    //Sales History
    Route::get('/sales_history', 'AudioTransactionController@salesHistory')->name('sales_history');
    Route::post('/sales_history_data', 'AudioTransactionController@salesHistoryData')->name('sales_history_data');

    //Payment History
    Route::get('/payment_history', 'AudioTransactionController@paymentHistory')->name('payment_history'); 
    Route::post('/payment_history_data', 'AudioTransactionController@paymentHistoryData')->name('payment_history_data');

    //Payment Request 
    Route::get('/admin/payment_request', 'AudioTransactionController@paymentRequest')->name('admin.payment_request'); 
    Route::post('/admin/payment_request_data', 'AudioTransactionController@paymentRequestData')->name('admin.payment_request_data');

    Route::patch('/admin/artistReleasePayment/{id}', 'AudioTransactionController@artistReleasePayment');
    Route::post('/admin/releasePaymentCallback', 'AudioTransactionController@releasePaymentRazorpayCallback');

    Route::post('/admin/artistReleasePaymentByPaypal', 'AudioTransactionController@artistReleasePaymentByPaypal');
    Route::get('/paypal/artistReleasePaymentStatus', 'AudioTransactionController@artistReleasePaymentPaypalStatus')->name('artistReleasePaymentStatus');
    Route::get('/checkout/artistReleasePaymentPaypal', 'AudioTransactionController@paypalCancelReturn');

    Route::post('/stripe/artistReleasePayment', 'AudioTransactionController@artistStripePayment')->name('stripe.artistReleasePayment');

    Route::post('/paystack/artistReleaseCallback', 'AudioTransactionController@paystackArtistReleaseCallback');
    
}); 
