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
Route::post('selected_user_warning', 'UsersController@selected_user_warning');
Route::post('del_selected_talk_img', 'TalkController@del_selected_talk_img');
Route::post('del_selected_user_talk', 'TalkController@del_selected_user_talk');
Route::post('user_force_stop', 'UsersController@user_force_stop');
Route::post('stop_app_use', 'UsersController@stop_app_use');

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

Route::post('ajax_talk_notice_table', 'NoticeController@ajax_talk_notice_table');
Route::post('add_talk', 'NoticeController@add_talk');
Route::post('get_talk_content', 'NoticeController@get_talk_content');
Route::post('remove_talk', 'NoticeController@remove_talk');

Route::post('ajax_message_table', 'NoticeController@ajax_message_table');
Route::post('add_message', 'NoticeController@add_message');
Route::post('get_message_content', 'NoticeController@get_message_content');
Route::post('remove_message', 'NoticeController@remove_message');

Route::post('ajax_sms_table', 'NoticeController@ajax_sms_table');
Route::post('add_sms', 'NoticeController@add_sms');
Route::post('get_sms_content', 'NoticeController@get_sms_content');
Route::post('remove_sms', 'NoticeController@remove_sms');

//cash_question manage
Route::get('cash_question', 'CashQuestionController@index');
Route::post('ajax_cash_question_table', 'CashQuestionController@ajax_cash_question_table');
Route::post('save_cash_question_opinion', 'CashQuestionController@save_cash_question_opinion');
Route::post('delete_cash_questin', 'CashQuestionController@delete_cash_questin');
Route::post('ajax_cash_declare_table', 'CashQuestionController@ajax_cash_declare_table');
Route::post('save_cash_declare', 'CashQuestionController@save_cash_declare');
Route::post('delete_cash_declare', 'CashQuestionController@delete_cash_declare');

//withdraw manage
Route::get('withdraw', 'WithdrawController@index');
Route::post('ajax_cash_table', 'WithdrawController@ajax_cash_table');
Route::post('ajax_withdraw_table', 'WithdrawController@ajax_withdraw_table');
Route::post('ajax_gifticon_table', 'WithdrawController@ajax_gifticon_table');
Route::post('ajax_present_table', 'WithdrawController@ajax_present_table');
Route::post('ajax_point_rank_table', 'UsersController@ajax_point_rank_table');

//statistic manage 
Route::get('statistic', 'StatisticController@index');
Route::post('ajax_edwards_table', 'StatisticController@ajax_edwards_table');
Route::post('ajax_connect_table', 'StatisticController@ajax_connect_table');

//admin notice manage
Route::get('admin_notice', 'AdminNoticeController@index');
Route::post('ajax_opinion_table', 'AdminNoticeController@ajax_opinion_table');
Route::post('save_opinion', 'AdminNoticeController@save_opinion');
Route::post('delete_opinion', 'AdminNoticeController@delete_opinion');
Route::post('ajax_manage_notice_table', 'AdminNoticeController@ajax_manage_notice_table');
Route::post('save_manage_notice', 'AdminNoticeController@save_manage_notice');
Route::post('delete_manage_notice', 'AdminNoticeController@delete_manage_notice');


Route::post('ajax_upload', 'BasicController@ajax_upload');
Route::get('file_download','BasicController@file_download');
