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

Route::get('/', 'HomeController@index');
Route::get('/home', 'UsersController@index');
Route::get('/login', 'Admin\AdminLoginController@index');
Route::get('/logout', 'Admin\AdminLoginController@doLogout');

Route::get('/notifications', 'NotificationsController@index');
Route::get('/setting', 'Admin\AdminSettingController@index');


Route::post('/login', array('as'=>"do_login", 'uses' => 'Admin\AdminLoginController@doLogin'));
Route::post('/setting', array('as'=>"do_setting", 'uses' => 'Admin\AdminSettingController@doSetting'));

// Agreement Pages
Route::get('/agreement/service', 'AgreementController@serviceAgreementPage');
Route::get('/agreement/gps', 'AgreementController@gpsAgreementPage');
Route::get('/agreement/privacy', 'AgreementController@privacyAgreementPage');

// notifiy page
Route::get('/notify', 'Admin\AdminSettingController@notify');
Route::get('/user_guide', 'Admin\AdminSettingController@use_guide');
Route::get('/google_card_register_guide', 'Admin\AdminSettingController@google_card_register_guide');
