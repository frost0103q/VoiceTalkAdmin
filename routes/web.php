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

Route::get('/', 'Admin\AdminLoginController@index');
Route::get('home', 'HomeController@index');
Route::get('login', 'Admin\AdminLoginController@index');
Route::get('logout', 'Admin\AdminLoginController@doLogout');

Route::get('/notifications', 'NotificationsController@index');
Route::get('setting', 'Admin\AdminSettingController@index');
Route::get('do_setting', 'Admin\AdminSettingController@doSetting');

Route::post('login', array('as'=>"do_login", 'uses' => 'Admin\AdminLoginController@doLogin'));

// Agreement Pages
Route::get('agree_photo', 'AgreementController@agree_photo');
Route::get('img_agree', 'AgreementController@img_agree');
Route::get('img_disagree', 'AgreementController@img_disagree');
Route::get('all_img_agree', 'AgreementController@all_img_agree');

Route::post('get_user_data', 'AgreementController@get_user_data');
Route::post('talk_confirm', 'AgreementController@talk_confirm');

Route::get('agree_voice', 'AgreementController@agree_voice');

Route::get('voice_agree', 'AgreementController@voice_agree');
Route::get('voice_disagree', 'AgreementController@voice_disagree');


// notifiy page
Route::get('/notify', 'Admin\AdminSettingController@notify');
Route::get('/user_guide', 'Admin\AdminSettingController@use_guide');
Route::get('/google_card_register_guide', 'Admin\AdminSettingController@google_card_register_guide');

//Talk and User manage
Route::get('talk_user_mgr', 'TalkController@index');
Route::post('ajax_user_table', 'UsersController@ajax_user_table');
Route::post('ajax_talk_table', 'TalkController@ajax_talk_table');
Route::post('ajax_declare_table', 'DeclareController@ajax_declare_table');
Route::post('del_selected_profile', 'UsersController@del_selected_profile');
Route::post('del_selected_warning', 'UsersController@del_selected_warning');
Route::post('del_selected_talk_img', 'TalkController@del_selected_talk_img');
Route::post('del_selected_user_talk', 'TalkController@del_selected_user_talk');

//Interdict Idiom Manage
Route::get('interdict_idiom_reg', 'IdiomController@index');
Route::post('save_interdict_idiom', 'IdiomController@save_interdict_idiom');
Route::post('del_selected_idiom', 'IdiomController@del_selected_idiom');

//notice manage
Route::get('notice_mgr', 'NoticeController@index');
Route::post('ajax_push_table', 'NoticeController@ajax_push_table');
Route::post('add_push', 'NoticeController@add_push');
Route::post('get_push_content', 'NoticeController@get_push_content');
Route::post('remove_push', 'NoticeController@remove_push');

Route::post('ajax_banner_table', 'NoticeController@ajax_banner_table');
Route::post('add_banner', 'NoticeController@add_banner');
Route::post('get_banner_content', 'NoticeController@get_banner_content');
Route::post('remove_banner', 'NoticeController@remove_banner');

//cash_question manage
Route::get('cash_question', 'CashQuestionController@index');

//withdraw manage
Route::get('withdraw', 'WithdrawController@index');

//statistic manage 
Route::get('statistic', 'StatisticController@index');

//admin notice manage
Route::get('admin_notice', 'AdminNoticeController@index');
