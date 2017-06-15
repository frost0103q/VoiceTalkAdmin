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
Route::get('/home', 'HomeController@index');
Route::get('/login', 'Admin\AdminLoginController@index');
Route::get('/logout', 'Admin\AdminLoginController@doLogout');

Route::get('/notifications', 'NotificationsController@index');
Route::get('/setting', 'Admin\AdminSettingController@index');
Route::get('/do_setting', 'Admin\AdminSettingController@doSetting');

Route::post('/login', array('as'=>"do_login", 'uses' => 'Admin\AdminLoginController@doLogin'));
Route::post('/setting', array('as'=>"do_setting", 'uses' => 'Admin\AdminSettingController@doSetting'));

// Agreement Pages
Route::get('/agree/non_agree_img', 'AgreementController@non_agree_img');
Route::get('/agree/profile_img', 'AgreementController@profile_img');
Route::get('/agree/talk_img', 'AgreementController@talk_img');

// notifiy page
Route::get('/notify', 'Admin\AdminSettingController@notify');
Route::get('/user_guide', 'Admin\AdminSettingController@use_guide');
Route::get('/google_card_register_guide', 'Admin\AdminSettingController@google_card_register_guide');
