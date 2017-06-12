<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function(){
	return "ok";
});

// init api
Route::get('/getInitInformation', 'UsersController@getInitInformation');

// user api
Route::post('/user', 'UsersController@doUser');
Route::get('/duplicateUser', 'UsersController@duplicateUser');
Route::get('/getUserList', 'UsersController@getUserList');
Route::post('/sendPresent', 'UsersController@sendPresent');
Route::post('/declareUser', 'UsersController@declareUser');
Route::post('/updateUserLocation', 'UsersController@updateLocation');
Route::post('/setAlarmFlag', 'UsersController@setAlarmFlag');
Route::post('/sendAlarm', 'UsersController@sendAlarm');
Route::post('/checkRoll', 'UsersController@checkRoll');
Route::post('/logForVoiceChat', 'UsersController@logForVoiceChat');

// user relation api
Route::post('/addFriend', 'UserRelationController@addFriend');
Route::post('/deleteFriend', 'UserRelationController@deleteFriend');
Route::post('/deleteAllFriend', 'UserRelationController@deleteAllFriend');
Route::post('/setUserRelation', 'UserRelationController@setUserRelation');
Route::get('/friendList', 'UserRelationController@getFriendList');

// auth api
Route::post('/requestAuthNum', 'UsersController@requestAuthNumber');
Route::post('/checkAuthNum', 'UsersController@checkAuthNumber');
Route::post('/autoRemoveAuth', 'UsersController@autoRemoveAuth');

// talk api
Route::post('/talk', 'TalkController@doTalk');
Route::get('/talk', 'TalkController@talk');
Route::get('/talkList', 'TalkController@talkList');
Route::post('/writeTalkReview', 'TalkController@writeReview');
Route::get('/checkDuplicateTalk', 'TalkController@duplicateTalk');


// notification api
Route::post('/notification', 'NotificationsController@doNotification');
Route::get('/notificationList', 'NotificationsController@notificationList');
Route::post('/sendEnvelop', 'NotificationsController@sendEnvelop');
Route::post('/sendGroupEnvelop', 'NotificationsController@sendGroupEnvelop');
Route::post('/setAllEnvelopFlag', 'NotificationsController@setAllEnvelopFlag');

// withdraw api
Route::post('/withdraw', 'WithdrawController@doWithdraw');
Route::get('/withdrawList', 'WithdrawController@withdrawList');

// pointhistory api
Route::post('/pointhistory', 'PointHistoryController@doPointHistory');
Route::get('/pointhistoryList', 'PointHistoryController@pointHistoryList');

// xmpp history
Route::get('/getXmppHistory', 'BasicController@getXmppHistory');
