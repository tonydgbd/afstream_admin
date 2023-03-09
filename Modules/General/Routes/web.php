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
    Route::get('/faq','GeneralController@faqs')->name('faq');
    Route::post('/faqsData','GeneralController@faqsData')->name('show_faq');
    Route::get('/updateFaq/{id}','GeneralController@updateFaq')->name('edit.faq');
    Route::patch('/updateFaqStatus/{id}','GeneralController@updateFaqStatus');
    Route::get('/addFaq','GeneralController@addFaq')->name('addFaq');
    Route::post('/addUpdateFaq/{id}','GeneralController@addUpdateFaq')->name('faq.update');
    Route::post('/destroyFaq/{id}','GeneralController@destroyFaq')->name('faq.destroy');
    Route::delete('/bulkDeleteFaq','GeneralController@bulkDeleteFaq')->name('bulkDeleteFaq');
    Route::get('/blog','GeneralController@blogs')->name('blog');
    Route::get('/blog_category','GeneralController@blog_category')->name('blog_category');
    Route::post('/blogsCategoryData','GeneralController@blogsCategoryData')->name('blogsCategoryData');
    Route::post('/createBlogCat/{id}','GeneralController@createBlogCat')->name('createBlogCat');
    Route::get('/getBlogCategoryName/{id}','GeneralController@getBlogCategoryName')->name('getBlogCategoryName');
    Route::patch('/blogCatStts/{id}','GeneralController@blogCatStts')->name('blogCatStts');
    Route::post('/destroyBlogCat/{id}','GeneralController@destroyBlogCat')->name('destroyBlogCat');
    Route::delete('/bulkDeleteBlogCat','GeneralController@bulkDeleteBlogCat')->name('bulkDeleteBlogCat');
    Route::delete('/bulkDeleteBlog','GeneralController@bulkDeleteBlog')->name('bulkDeleteBlog');
    Route::post('/blogsData','GeneralController@blogsData')->name('blogsData');
    Route::patch('/blogStts/{id}','GeneralController@blogStts')->name('blogStts');
    Route::get('/create_blog','GeneralController@create_blog')->name('create_blog');
    Route::get('/editBlog/{id}','GeneralController@editBlog')->name('editBlog');
    Route::post('/addEditBlog/{id}','GeneralController@addEditBlog')->name('addEditBlog');
    Route::post('/destroyBlog/{id}','GeneralController@destroyBlog')->name('destroyBlog');
    
    Route::get('/pages','GeneralController@pages')->name('pages');
    Route::post('/pagesData','GeneralController@pagesData')->name('pagesData');
    Route::get('/create_page','GeneralController@create_page')->name('create_page');
    Route::post('/addEditPage/{id}','GeneralController@addEditPage')->name('addEditPage');
    Route::patch('/pagesStts/{id}','GeneralController@pagesStts')->name('pagesStts');
    Route::get('/editPage/{id}','GeneralController@editPage')->name('editPage');
    Route::post('/destroyPage/{id}','GeneralController@destroyPage')->name('destroyPage');
    Route::delete('/bulkDeletePages','GeneralController@bulkDeletePages')->name('bulkDeletePages');
    
    
    Route::get('/testimonial','GeneralController@testimonial')->name('testimonial');
    Route::get('/testimonial/create','GeneralController@createTestimonial')->name('testimonial.create');
    Route::get('/testimonial/edit/{id}','GeneralController@editTestimonial')->name('testimonial.edit');
    Route::post('/addEditTestimonial/{id}','GeneralController@addEditTestimonial')->name('addEditTestimonial');
    Route::post('/testimonialData','GeneralController@testimonialData')->name('testimonialData');
    Route::patch('/tesimonialStts/{id}','GeneralController@tesimonialStts')->name('tesimonialStts');
    Route::post('/destroyTestimonial/{id}','GeneralController@destroyTestimonial')->name('destroyTestimonial');
    Route::delete('/bulk_delete/testimonial','GeneralController@bulkDeleteTestimonial')->name('testimonial.delete');
    
    Route::get('/slider','GeneralController@slider')->name('slider');
    Route::post('/sliderData','GeneralController@sliderData')->name('sliderData');
    Route::delete('/bulk_delete/slider','GeneralController@bulkDeleteSlider')->name('slider.delete');
    Route::get('/slider/create','GeneralController@create_slider')->name('create_slider');
    Route::get('/slider/edit/{id}','GeneralController@edit_slider')->name('editSlider');
    Route::post('/addEditSlider/{id}','GeneralController@addEditSlider')->name('addEditSlider');
    Route::patch('/updateSliderStatus/{id}','GeneralController@updateSliderStatus')->name('updateSliderStatus');
    Route::post('/destroySlider/{id}','GeneralController@destroySlider')->name('destroySlider');
    Route::delete('/bulk_delete/slider','GeneralController@bulkDeleteSlider')->name('slider.delete');
    
    Route::post('/saveSliderPosition','GeneralController@saveSliderPosition')->name('saveSliderPosition');

    Route::get('/comments/{type}/{name}/{id}','GeneralController@comments')->name('comments');
    Route::post('/commentData/{type}/{id}','GeneralController@commentData')->name('commentData');
    Route::patch('/comment/status/{id}','GeneralController@updateCommentStts');
    Route::post('/comment/destroy/{id}','GeneralController@destroyComment');
    Route::post('/getReply/{id}','GeneralController@get_reply_data');
    Route::post('/comment/reply/{type}/{cmnt_id}/{blog_id}','GeneralController@replyComment');
    Route::delete('/bulk_delete/comment','GeneralController@bulkDeleteComment')->name('comment.delete');

    Route::post('/audio/rating','GeneralController@audio_rating');
    Route::get('/manual_transaction', 'GeneralController@manual_transaction')->name('manual_transaction');
    Route::post('/manualPayData', 'GeneralController@manualPayData')->name('manualPayData');


    Route::get('/invoice_setting', 'GeneralController@invoice_setting')->name('invoice_setting');
    Route::post('/saveInvoice', 'GeneralController@invoiceDetail')->name('invoiceDetail');
    
});
