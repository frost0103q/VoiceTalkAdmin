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
