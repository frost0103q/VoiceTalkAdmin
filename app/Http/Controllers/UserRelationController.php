<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/25/2017
 * Time: 1:38 PM
 */

namespace App\Http\Controllers;


use App\Models\AppUser;
use App\Models\ServerFile;
use App\Models\UserRelation;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;

class UserRelationController  extends BasicController{
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

    public function addFriend(HttpRequest $request) {
        $no = $request->input('user_no');
        $friend_no = $request->input('friend_user_no');

        if($no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $from_user = $results[0];

        $results = AppUser::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $to_user = $results[0];

        $relation = UserRelation::where('user_no', $friend_no)->where('relation_user_no', $no)->first();
        if($relation != null && $relation->is_block_friend == 1) {
            $response = config('constants.ERROR_BLOCKED_USER');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();
        if($results != null && $results->is_friend == 1) {
            $response = config('constants.ERROR_ALREADY_ADDED');
            return response()->json($response);
        }

        if($results == null) {
            $friend = new UserRelation();
        }
        else {
            $friend = $results;
        }

        $friend->user_no = $no;
        $friend->relation_user_no = $friend_no;
        $friend->is_friend = 1;
        $friend->save();
        $response['no'] = $friend->no;

        $message = [];
        $message['type'] = config('constants.CHATMESSAGE_TYPE_ADD_FRIEND');
        $message['user_no'] = $from_user->no;
        $message['user_name'] = $from_user->nickname;

        $file = ServerFile::where('no', $from_user->img_no)->first();
        if($file != null) {
            $message['user_img_url'] = $file->path;
        }
        else {
            $message['user_img_url'] = "";
        }
        $message['time'] = "";
        $message['content'] = $from_user->nickname."님이 당신을 친구추가했습니다.";
        $message['title'] = "친구추가";
        $message['talk_no'] = "";
        $message['talk_user_no'] = "";

        $this->sendAlarmMessage($from_user->no, $to_user->no, $message, null);

        return response()->json($response);
    }

    public function setUserRelation(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $relation_user_no = $request->input('relation_user_no');
        $flag = $request->input('flag');

        if($user_no == null || $relation_user_no == null || $flag == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = AppUser::where('no', $relation_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $user_no)->where('relation_user_no', $relation_user_no)->first();

        if($results == null) {
            $friend = new UserRelation();
        }
        else {
            $friend = $results;
        }

        $friend->user_no = $user_no;
        $friend->relation_user_no = $relation_user_no;
        $friend->is_friend = 0;

        if($flag == config('constants.USER_RELATION_FLAG_BLOCK_FRIEND')) {
            $friend->is_block_friend = 1;
        }

        if($flag == config('constants.USER_RELATION_FLAG_UNBLOCK_FRIEND')) {
            $friend->is_block_friend = 0;
        }

        if($flag == config('constants.USER_RELATION_FLAG_ENABLE_ALARM')) {
            $friend->is_alarm = 1;
        }

        if($flag == config('constants.USER_RELATION_FLAG_DISABLE_ALARM')) {
            $friend->is_alarm = 0;
        }

        $friend->save();
        $response['no'] = $friend->no;

        return response()->json($response);
    }

    public function getFriendList(HttpRequest $request) {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if($page == null) {
            $params['page'] = 1;
        }

        $arr_friend_no_dict = DB::table('t_user_relation')
            ->select('relation_user_no')
            ->where('user_no', $user_no)
            ->where('is_friend', 1)->get();

        $arr_friend_no = array();
        for($i = 0; $i < count($arr_friend_no_dict); $i++) {
            $freind_no = $arr_friend_no_dict[$i];
            array_push($arr_friend_no, $freind_no->relation_user_no);
        }

        $query = DB::table('t_user')
                    ->select('t_user.*')
                    ->whereIn('no', $arr_friend_no)
                    ->offset($limit*($page - 1))->limit($limit);

        $response = $query->get();
        for($i = 0; $i < count($response); $i++) {
            $user = $response[$i];
            $img = ServerFile::where('no', $user->img_no)->first();
            if($img != null) {
                $user->img_url = $img->path;
            }
            else {
                $user->img_url = "";
            }
        }
        return response()->json($response);
    }

    public function deleteAllFriend(HttpRequest $request) {
        $no = $request->input('user_no');
        if($no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $update_data = [];
        $update_data['is_friend'] = 0;

        $results = UserRelation::where('user_no', $no)->update($update_data);
        $response = config('constants.ERROR_NO');

        return response()->json($response);
    }

    public function deleteFriend(HttpRequest $request) {
        $no = $request->input('user_no');
        $friend_no = $request->input('friend_user_no');

        if($no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = AppUser::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();

        if($results == null) {
            $friend = new UserRelation();
        }
        else {
            $friend = $results;
        }

        $friend->user_no = $no;
        $friend->relation_user_no = $friend_no;
        $friend->is_friend = 0;
        $friend->save();
        $response['no'] = $friend->no;

        return response()->json($response);
    }
}