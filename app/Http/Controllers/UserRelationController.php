<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/25/2017
 * Time: 1:38 PM
 */

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserRelation;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;

class UserRelationController extends BasicController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return view('welcome');
    }

    public function getUserRelation(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $friend_no = $request->input('relation_user_no');

        if ($user_no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('no', $user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $relation = UserRelation::where('user_no', $user_no)->where('relation_user_no', $friend_no)->first();
        if($relation == null) {
            $relation = new UserRelation();
            $relation->user_no = $user_no;
            $relation->relation_user_no = $friend_no;
        }
        return response()->json($relation);
    }

    public function addFriend(HttpRequest $request)
    {
        $no = $request->input('user_no');
        $friend_no = $request->input('friend_user_no');

        if ($no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $from_user = $results[0];

        $results = User::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $to_user = $results[0];

        $results = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();
        if ($results != null && $results->is_friend == config('constants.TRUE')) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        if ($results == null) {
            $friend = new UserRelation();
        } else {
            $friend = $results;
        }

        $friend->user_no = $no;
        $friend->relation_user_no = $friend_no;
        $friend->is_friend = 1;
        $friend->save();
        $response['no'] = $friend->no;

        $ret = $this->sendAlarmMessage($from_user->no, $to_user->no, config('constants.NOTI_TYPE_ADD_FRIEND'));

        return response()->json($ret);
    }

    public function setUserRelation(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $relation_user_no = $request->input('relation_user_no');
        $flag = $request->input('flag');

        if ($user_no == null || $relation_user_no == null || $flag == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $relation_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $user_no)->where('relation_user_no', $relation_user_no)->first();

        if ($results == null) {
            $friend = new UserRelation();
            $friend->is_friend = config('constants.FALSE');
        } else {
            $friend = $results;
        }

        $friend->user_no = $user_no;
        $friend->relation_user_no = $relation_user_no;

        if ($flag == config('constants.USER_RELATION_FLAG_ENABLE_ALARM')) {
            $friend->is_alarm = config('constants.TRUE');
        }

        if ($flag == config('constants.USER_RELATION_FLAG_DISABLE_ALARM')) {
            $friend->is_alarm = config('constants.FALSE');
        }

        $friend->save();
        $response['no'] = $friend->no;

        if ($flag == config('constants.USER_RELATION_FLAG_ENABLE_ALARM')) {
            $this->sendAlarmMessage($user_no, $relation_user_no, config('constants.NOTI_TYPE_ALARM_ENABLE'));
        }
        if ($flag == config('constants.USER_RELATION_FLAG_DISABLE_ALARM')) {
            $this->sendAlarmMessage($user_no, $relation_user_no, config('constants.NOTI_TYPE_ALARM_DISABLE'));
        }

        return response()->json($response);
    }

    public function getFriendList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $arr_friend_no_dict = DB::table('t_user_relation')
            ->select('relation_user_no')
            ->where('user_no', $user_no)
            ->where('is_friend', config('constants.TRUE'))->get();

        $arr_friend_no = array();
        for ($i = 0; $i < count($arr_friend_no_dict); $i++) {
            $freind_no = $arr_friend_no_dict[$i];
            array_push($arr_friend_no, $freind_no->relation_user_no);
        }

        $query = User::whereIn('no', $arr_friend_no)->offset($limit * ($page - 1))->limit($limit);

        $response = $query->get();
        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->fillInfo();
        }
        return response()->json($response);
    }

    public function deleteAllFriend(HttpRequest $request)
    {
        $no = $request->input('user_no');
        if ($no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $update_data = [];
        $update_data['is_friend'] = config('constants.FALSE');

        $results = UserRelation::where('user_no', $no)->update($update_data);
        $response = config('constants.ERROR_NO');

        return response()->json($response);
    }

    public function deleteFriend(HttpRequest $request)
    {
        $no = $request->input('user_no');
        $friend_no = $request->input('friend_user_no');

        if ($no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();

        if ($results == null) {
            $friend = new UserRelation();
        } else {
            $friend = $results;
        }

        $friend->user_no = $no;
        $friend->relation_user_no = $friend_no;
        $friend->is_friend = config('constants.FALSE');
        $friend->save();
        $response['no'] = $friend->no;

        return response()->json($response);
    }

    public function isAlarmBlockingUser(HttpRequest $request)
    {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');

        if ($from_user_no == null || $to_user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $from_user_no)->where('relation_user_no', $to_user_no)->first();

        if ($results != null && $results->is_alarm == config('constants.FALSE')) {
            $response->is_alarm = config('constants.FALSE');
            return response()->json($response);
        }

        $response->is_alarm = config('constants.TRUE');
        return response()->json($response);
    }
}
