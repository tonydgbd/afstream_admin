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
Route::group(['middleware'=>['auth','admin.auth']], function(){
    Route::get('/seo', 'SettingController@seo')->name('seo');
    Route::post('/seo_update', 'SettingController@seo_update')->name('seo.update');
    Route::post('/seo_add', 'SettingController@seo_add')->name('seo.add');
    
    Route::get('/site', 'SettingController@site')->name('site');
    Route::post('/site_update', 'SettingController@site_update')->name('site.update');

    Route::get('/mail', 'ConfigController@mail')->name('mail');
    Route::post('/mail_update', 'ConfigController@mail_update')->name('mail.update');

    Route::get('/api', 'ConfigController@api')->name('api');
    Route::post('/api_update/{type}', 'ConfigController@api_update')->name('api.update');
    
    Route::get('/social_login', 'ConfigController@social_login')->name('social_login');
    Route::post('/updateStatus', 'ConfigController@updateStatus')->name('updateStatus');
    Route::post('/saveSocialLoginData/{type}', 'ConfigController@saveSocialLoginData')->name('saveSocialLoginData');
    
    Route::post('/changeAdsenseStatus/{id}', 'SettingController@changeAdsenseStatus')->name('changeAdsenseStatus');
    Route::get('/notifications', 'SettingController@notifications')->name('notifications');   
    Route::get('/notification/add', 'SettingController@showAddNotification')->name('user.notification');
    Route::post('/notificationData', 'SettingController@notificationData')->name('notificationData');
    Route::post('/addNotification', 'SettingController@addNotification')->name('notification.add');
    Route::post('/notification/destroy/{id}/{user_id}', 'SettingController@destroyNotification')->name('destroyNotification');
    Route::delete('/bulkDelete_notification', 'SettingController@bulkDeleteNotification')->name('bulkDeleteNotification');
    Route::post('/newsletter/save', 'ConfigController@saveNewsletterApi')->name('newsletter.save');
    Route::post('/donation/save', 'ConfigController@saveDonationLink')->name('donation.save');
    Route::get('/currency', 'SettingController@currency_setting')->name('currency');
    Route::post('/currency/save', 'SettingController@saveCurrency')->name('currency.save');
    Route::post('/currency/update/{id}', 'SettingController@updateCurrency')->name('currency.update');
    Route::post('/auto_update/rate', 'SettingController@auto_update_rate');
    Route::post('currency_detail/{id}', 'SettingController@currency_detail');
    Route::post('currency/destroy/{id}', 'SettingController@destroyCurrency');
    Route::post('/tax/save', 'SettingController@saveTax')->name('tax.save');
    Route::post('/commission/save', 'SettingController@saveCommission')->name('commission.save');
    
    Route::get('/google/ad', 'SettingController@google_ad')->name('show_google_ad');
    Route::post('/google_ad/{id}', 'SettingController@saveGoogleAd')->name('google_ad');
    Route::get('/commonsetting', 'SettingController@common_setting')->name('commonsetting');
    Route::post('/saveCommonSetting/{type}', 'SettingController@saveCommonSetting')->name('saveCommonSetting');


    ////// menu setting
    Route::get('/menusetting', 'SettingController@menuSetting')->name('menu.setting');
    Route::post('/show_menu', 'SettingController@menuData')->name('show_menu');
    Route::get('/create_menu', 'SettingController@create_menu')->name('create_menu');
    Route::post('/saveMenu/{id}', 'SettingController@saveMenu')->name('menu.save');
    Route::get('/edit_menu/{id}', 'SettingController@edit_menu')->name('edit_menu');
    Route::patch('/update_menu_status/{id}', 'SettingController@updateMenuStatus')->name('update_menu_status');
    Route::post('/destroyMenu/{id}', 'SettingController@destroyMenu')->name('destroyMenu');
    Route::delete('/bulkDeleteMenu', 'SettingController@bulkDeleteMenu')->name('bulkDeleteMenu');
    // Route::post('/saveMenu', 'SettingController@saveMenu')->name('manu.save');


    ////////////////////// admin setting
    Route::get('/adminsetting', 'SettingController@adminsetting')->name('adminsetting');
    Route::post('/dashbord-setting', 'SettingController@dashboardSetting')->name('dashbord-setting');

    Route::post('currencyData', 'SettingController@currencyData')->name('currencyData');
    Route::post('make_default', 'SettingController@make_default_curr');

    Route::get('/open_exchange', 'ConfigController@open_exchange')->name('open_exchange');
    Route::post('/save_exchange_key', 'ConfigController@save_exchange_key')->name('open_exchange.save');
    Route::get('/taxn_commission', 'SettingController@tax')->name('taxn_commission');    
 
    //3rd Party Integration
    Route::get('/integration', 'ConfigController@integration')->name('integration');  
    Route::post('/integration_changeStatus', 'ConfigController@integration_changeStatus')->name('integration.changeStatus');
    Route::post('/saveIntegration/{type}', 'ConfigController@saveIntegrationData')->name('saveIntegrationData');
});
