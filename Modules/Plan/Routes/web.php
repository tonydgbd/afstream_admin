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
    Route::get('/plans', 'PlanController@index')->name('plans');
    Route::post('/planData', 'PlanController@planData')->name('planData');
    Route::get('/plan/create', 'PlanController@create')->name('plan.create');
    Route::get('/plan/edit/{create}', 'PlanController@edit')->name('plan.edit');
    Route::post('/plan/addEditplan/{create}', 'PlanController@addEditPlan')->name('addEditPlan');
    Route::delete('/plan/bulk_delete', 'PlanController@bulkDeletePlanData')->name('plan.bulk_delete');
    Route::post('/plan/destroy/{id}', 'PlanController@destroyPlan')->name('plan.destroy');
    Route::patch('/plan/status/{id}', 'PlanController@updatePlanStatus');
    Route::patch('/plan/is_download/{id}', 'PlanController@updateDownloadStatus');
    Route::patch('/plan/show_adv/{id}', 'PlanController@updateAdvStatus');
    
});

