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
    Route::get('/coupon_management', 'CouponController@index')->name('coupon_management');
    Route::get('/coupon/create', 'CouponController@createCoupon')->name('coupon.create');
    Route::get('/coupon/edit/{id}', 'CouponController@editCoupon')->name('coupon.edit');
    Route::post('/addEditCoupon/{id}', 'CouponController@addEditCoupon')->name('addEditCoupon');
    Route::post('/couponData', 'CouponController@couponData')->name('couponData');
    Route::post('/coupon/destroy/{id}', 'CouponController@destroyCoupon');
    Route::delete('/bulk_delete/coupon', 'CouponController@bulkDeleteCoupon')->name('bulkDeleteCoupon');
    Route::patch('/coupon/status/{id}', 'CouponController@updateCouponStatus');
});
