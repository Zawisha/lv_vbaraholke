<?php

use Illuminate\Http\Request;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
 //   return $request->user();
//});

Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');
Route::post('/confirm_token', 'API\AuthController@confirm_token');
Route::post('/select_region', 'board\BoardController@select_region');
Route::post('/select_category', 'board\BoardController@select_category');
Route::post('/render_posts_list', 'PostsController@render_posts_list');
Route::post('/count_posts', 'PostsController@count_posts');
Route::post('/get_post_data', 'PostsController@get_post_data');
Route::post('/vk_first', 'VkController@vk_first');
Route::post('/is_ban', 'API\AuthController@is_ban');

Route::post('/my_event', 'ChatController@my_event');
Route::post('/get_user_info_channel', 'API\AuthController@get_user_info_channel');




Route::middleware('auth:api')->group(function () {
    Route::middleware('\App\Http\Middleware\CheckAdmin')->group(function () {
        Route::post('/users_list_pagination', 'admin\AdminController@users_list_pagination');
        Route::post('/users_list', 'admin\AdminController@users_list');
        Route::post('/change_user_role', 'admin\AdminController@change_user_role');
        Route::post('/change_user_banned', 'admin\AdminController@change_user_banned');
        Route::post('/render_posts_list_admin', 'admin\AdminController@render_posts_list_admin');
        Route::post('/count_posts_admin', 'admin\AdminController@count_posts_admin');
        Route::post('/save_posts_admin', 'admin\AdminController@save_posts_admin');
    });

    Route::post('/logout', 'API\AuthController@logout');
    Route::post('/is_pas', 'API\AuthController@is_pas');
    Route::get('/get-user', 'API\AuthController@getUser');
//    Route::get('/get-user', 'API\AuthController@getUser')->middleware('\App\Http\Middleware\CheckBan');
    Route::get('/get_user_info', 'API\AuthController@get_user_info');
    Route::post('/store_pic', 'image\ImageController@store_pic')->middleware('\App\Http\Middleware\CheckBan');
    Route::post('/store_pic_chat', 'image\ImageController@store_pic_chat');
    Route::post('/store_ava', 'image\ImageController@store_ava');
    Route::post('/del_ava', 'image\ImageController@del_ava');
    Route::post('/check_ava', 'image\ImageController@check_ava');
    Route::post('/is_admin', 'admin\AdminController@is_admin');
    Route::post('/render_user_posts_list', 'PostsController@render_user_posts_list');
    Route::post('/count_posts_user', 'PostsController@count_posts_user');
    Route::post('/user_delete_post', 'PostsController@user_delete_post');
    Route::post('/reset_password', 'API\AuthController@reset_password');
    Route::post('/set_password', 'API\AuthController@set_password');
    Route::post('/change_mobile', 'API\AuthController@change_mobile');
    Route::post('/change_email', 'API\AuthController@change_email');
    Route::post('/get_users_with_dialogs', 'ChatController@get_users_with_dialogs');
    Route::post('/get_data_props_user', 'ChatController@get_data_props_user');
    Route::post('/get_data_scroll_user', 'ChatController@get_data_scroll_user');
    Route::post('/save_message_chat', 'ChatController@save_message_chat');

    Route::post('/admin_delete_post', 'admin\AdminController@admin_delete_post');


});
