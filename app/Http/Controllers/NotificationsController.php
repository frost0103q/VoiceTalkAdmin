<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserRelation;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;

class NotificationsController extends BasicController
{
    /*
     |--------------------------------------------------------------------------
     | HomeController Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /******************************************************************
     *
     * View Functions
     *
     ******************************************************************/
    public function index()
    {
        $params = Request::all();

        if (count($params) == 0) {
            $params['rows'] = Config::get('config.itemsPerPage.default');
            $params['page'] = 1;
        }

        return view('notifications.index', ["params" => $params]);
    }

    /******************************************************************
     *
     * Private Functions
     *
     ******************************************************************/


    /******************************************************************
     *
     * Public Functions
     *
     ******************************************************************/
    private function testFunction()
    {

    }

    /******************************************************************
     *
     * API Functions
     *
     ******************************************************************/
    /**
     * getUserList
     *
     * @return json user arrya
     */

    public function getUserUnreadCnt($user_no)
    {
        $unread_cnt =  Notification::where('to_user_no', $user_no)
                                ->where('is_read', config('constants.UNREAD')) ->count();
        return $unread_cnt;
    }

    public function getChatRoomInfo($from_user_no, $peer_user_no) {
        // 마지막 Message
        $last_notifcation =  Notification::where(function ($q) use ($from_user_no, $peer_user_no) {
            $q->where(function ($q) use ($from_user_no, $peer_user_no) {
                $q->where('from_user_no', $from_user_no)->where('to_user_no', $peer_user_no);
            })
                ->orWhere(function ($q) use ($from_user_no, $peer_user_no) {
                    $q->where('to_user_no', $from_user_no)->where('from_user_no', $peer_user_no);
                });
        })->orderBy('created_militime', 'desc')->limit(1)->first();

        // 상대유저정보
        $user = User::where('no', $peer_user_no)->first();
        $user_relation = UserRelation::where('user_no', $from_user_no)->where('relation_user_no', $peer_user_no)->first();
        $other_user_relation = UserRelation::where('user_no', $peer_user_no)->where('relation_user_no', $from_user_no)->first();

        // unread_cnt
        $unread_cnt =  Notification::where('from_user_no', $peer_user_no)->where('to_user_no', $from_user_no)
            ->where('is_read', config('constants.UNREAD')) ->count();

        $user->fillInfo();
        $chat_room = $last_notifcation;
        $chat_room->user = $user;
        $chat_room->user_relation = $user_relation;
        $chat_room->other_user_relation = $other_user_relation;
        $chat_room->from_user_no = $from_user_no;
        $chat_room->to_user_no = $peer_user_no;
        $chat_room->unread_cnt = $unread_cnt;

        return $chat_room;
    }

    public function getChatRoomList($user_no, $page, $limit) {
        // 1. 채팅방 얻기
        $sql = "select (SELECT max(created_militime) latest FROM t_notification WHERE (t_notification.from_user_no = a.from_user_no and t_notification.to_user_no = a.to_user_no) or 
                 (t_notification.from_user_no = a.to_user_no and t_notification.to_user_no = a.from_user_no)) as latest, a.from_user_no, a.to_user_no from ";

        if($user_no != null) {
            $sql .= "(select from_user_no, to_user_no from t_notification where from_user_no=$user_no group by from_user_no, to_user_no";
            $sql .= " UNION SELECT to_user_no as from_user_no, from_user_no as to_user_no from t_notification where to_user_no=$user_no group by from_user_no, to_user_no) a";
        }
        else {
            $sql .= "(select from_user_no, to_user_no from t_notification group by from_user_no, to_user_no";
            $sql .= " UNION SELECT to_user_no as from_user_no, from_user_no as to_user_no from t_notification group by from_user_no, to_user_no) a";
        }
        $sql = $sql . " ORDER BY latest DESC";
        $sql .= " Limit " . $limit . " OFFSET " . ($limit * ($page - 1));

        $response = DB::select($sql);

        // 2. 채팅방별 리스트정보얻기. (마지막 message, 상대유저, unread_cnt)
        $arr_chat_room = array();
        for ($i = 0; $i < count($response); $i++) {
            $chat_room = $response[$i];
            $from_user_no = $chat_room->from_user_no;
            $peer_user_no = $chat_room->to_user_no;

            $chat_room_info = $this->getChatRoomInfo($from_user_no, $peer_user_no);

            if($chat_room_info != null) {
                array_push($arr_chat_room, $chat_room_info);
            }
        }

        return $arr_chat_room;
    }

    public function getFriendRoomList($user_no, $page, $limit) {

        $sql = "select (SELECT max(created_militime) latest FROM t_notification WHERE (t_notification.from_user_no = a.from_user_no and t_notification.to_user_no = a.to_user_no) or 
                 (t_notification.from_user_no = a.to_user_no and t_notification.to_user_no = a.from_user_no)) as latest, a.from_user_no, a.to_user_no from ";

        $sql .= " (select $user_no as from_user_no , relation_user_no as to_user_no from t_user_relation where user_no=$user_no and is_friend =".config('constants.TRUE').") as a";
        $sql = $sql . " ORDER BY latest DESC";
        $sql .= " Limit " . $limit . " OFFSET " . ($limit * ($page - 1));

        $response = DB::select($sql);

        // 2. 채팅방별 리스트정보얻기. (마지막 message, 상대유저, unread_cnt)
        $arr_chat_room = array();
        for ($i = 0; $i < count($response); $i++) {
            $chat_room = $response[$i];
            $from_user_no = $chat_room->from_user_no;
            $peer_user_no = $chat_room->to_user_no;

            $chat_room_info = $this->getChatRoomInfo($from_user_no, $peer_user_no);

            if($chat_room_info != null) {
                array_push($arr_chat_room, $chat_room_info);
            }
        }

        return $arr_chat_room;
    }

    public function chatRoomList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $user_no = $request->input('user_no');
        $search_type = $request->input('search_type') == null ? -1 : $request->input('search_type');

        if($search_type == -2) { // 친구
            $response = $this->getFriendRoomList($user_no, $page, $limit);
        }
        else { // 전체
            $response = $this->getChatRoomList($user_no, $page, $limit);
        }

        return response()->json($response);
    }

    public function notificationListInChatRoom(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');

        $response = Notification::select('*');
        $response = $response->where(
            function ($q) use ($from_user_no, $to_user_no) {
                $q->where(function ($q) use ($from_user_no, $to_user_no) {
                    $q->where(['from_user_no' => $from_user_no, 'to_user_no' => $to_user_no]);
                    $q->orWhere(['to_user_no' => $from_user_no, 'from_user_no' => $to_user_no]);
                });
        });
        $response = $response->orderBy('created_militime', 'desc')->offset($limit * ($page - 1))->limit($limit)->get();

        for ($i = 0; $i < count($response); $i++) {
            if ($response[$i]->from_user_no == $from_user_no) {
                $user = User::where('no', $response[$i]->to_user_no)->first();
                $user_relation = UserRelation::where('user_no', $from_user_no)->where('relation_user_no', $response[$i]->to_user_no)->first();
            } else {
                $user = User::where('no', $response[$i]->from_user_no)->first();
                $user_relation = UserRelation::where('user_no', $from_user_no)->where('relation_user_no', $response[$i]->from_user_no)->first();
            }

            $user->fillInfo();
            $response[$i]->user = $user;
            $response[$i]->user_relation = $user_relation;
        }

        return response()->json($response);
    }

    private function pointForEnvelop($from_user_no, $to_user_no) {

        $results = User::where('no', $from_user_no)->get();
        if ($results == null || count($results) == 0) {
            return false;
        }
        $from_user = $results[0];

        $results = User::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            return false;
        }
        $to_user = $results[0];

        // 대화방의 첫대화얻기
        $chat_room  = Notification::where(function ($q) use ($from_user_no, $to_user_no) {
                                                $q->where(function ($q) use ($from_user_no, $to_user_no) {
                                                    $q->where('from_user_no', $from_user_no)->where('to_user_no', $to_user_no);
                                                })->orWhere(function ($q) use ($from_user_no, $to_user_no) {
                                                    $q->where('to_user_no', $from_user_no)->where('from_user_no', $to_user_no);
                                                });
                                    })->where(function ($q){
                                                $q->where('type', config('constants.NOTI_TYPE_SEND_ENVELOP'))
                                                    ->orWhere('type', config('constants.NOTI_TYPE_CHATMESSAGE'));
                                    })->orderBy('created_militime')->limit(1)->first();

        if($chat_room != null) {
            $chatroom_from_user_no = $chat_room->from_user_no;
            $chatroom_to_user_no = $chat_room->to_user_no;
        }
        else {
            $chatroom_from_user_no = $from_user_no;
            $chatroom_to_user_no = $to_user_no;
        }

        if($chatroom_from_user_no == $from_user_no) {
            $chatroom_from_user = $from_user;
            $chatroom_to_user = $to_user;
        }
        else {
            $chatroom_from_user = $to_user;
            $chatroom_to_user = $from_user;
        }

        $need_a_minus = false;
        $need_a_plus = false;
        $need_b_minus = false;
        $need_b_plus = false;

        // A상담=>B상담회원일 경우
        if($chatroom_from_user->verified == config('constants.VERIFIED') && $chatroom_to_user->verified == config('constants.VERIFIED')) {
            // A가 보낼때는 -20, 이때 B가 받으면 +14
            if($chatroom_from_user->no == $from_user_no) {
                $need_a_minus = true;
                $need_a_plus = false;
                $need_b_minus = false;
                $need_b_plus = true;
            }
            // B가 보낼때는 그냥, 이때 A가 받으면 그냥,
            else {
                $need_a_minus = false;
                $need_a_plus = false;
                $need_b_minus = false;
                $need_b_plus = false;
            }
        }
        //A상담=>B일반회원일 경우
        else if($chatroom_from_user->verified == config('constants.VERIFIED') && $chatroom_to_user->verified == config('constants.UNVERIFIED')) {
            // B에게서 첫메시지가 있는가 검사
            $first_b_message  = Notification::where('from_user_no', $chatroom_to_user->no)->where('to_user_no', $chatroom_from_user->no)->where(function ($q){
                                    $q->where('type', config('constants.NOTI_TYPE_SEND_ENVELOP'))
                                        ->orWhere('type', config('constants.NOTI_TYPE_CHATMESSAGE'));
                                })->limit(1)->first();


            // B가 보낸 첫메시지가 없는 경우
            if($first_b_message == null) {
                // A가 B한테 맹폭격처럼 메세지 보낼때는 -20 되다가
                if($chatroom_from_user->no == $from_user_no) {
                    $need_a_minus = true;
                    $need_a_plus = false;
                    $need_b_minus = false;
                    $need_b_plus = false;
                }
                else {
                    // B가 보낼때는 -20, 이때 A가 받으면 적립
                    $need_b_minus = true;
                    $need_a_plus = true;
                    $need_a_minus = false;
                    $need_b_plus = false;
                }
            }
            else {
                // 차감 중지, 이때 B는 포인트 그냥
                if($chatroom_from_user->no == $from_user_no) {
                    $need_a_minus = false;
                    $need_a_plus = false;
                    $need_b_minus = false;
                    $need_b_plus = false;
                }
                else {
                    // B가 보낼때는 -20, 이때 A가 받으면 적립
                    $need_b_minus = true;
                    $need_a_plus = true;
                    $need_a_minus = false;
                    $need_b_plus = false;
                }
            }
        }
        // A일반=>B상담회원일 경우
        else if($from_user->verified == config('constants.UNVERIFIED') && $to_user->verified == config('constants.VERIFIED')) {
            // A가 보낼때는 -20, 이때 B가 받으면 적립,
            if($chatroom_from_user->no == $from_user_no) {
                $need_a_minus = true;
                $need_a_plus = false;
                $need_b_minus = false;
                $need_b_plus = true;
            }
            else {
                // B가 보낼때는 그냥, 이때 A가 받으면 그냥
                $need_a_minus = false;
                $need_a_plus = false;
                $need_b_minus = false;
                $need_b_plus = false;
            }
        }
        //일반<=>일반회원일 경우
        else if($from_user->verified == config('constants.UNVERIFIED') && $to_user->verified == config('constants.UNVERIFIED')) {
            // A가 보낼때는 -20, 이때 B가 받으면 그냥,
            if($chatroom_from_user->no == $from_user_no) {
                $need_a_minus = true;
                $need_a_plus = false;
                $need_b_minus = false;
                $need_b_plus = false;
            }
            else {
                // B가 보낼때는 -20, 이때 A가 받으면 그냥
                $need_b_minus = true;
                $need_a_minus = false;
                $need_a_plus = false;
                $need_b_plus = false;
            }
        }

        $pointRule = config('constants.POINT_ADD_RULE');
        $need_point = $pointRule[config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE')];

        if($need_a_minus == true) {
            $user_controller = new UsersController();
            $avablable_point = $user_controller->getAvailableUserPoint($chatroom_from_user->no);

            if($avablable_point < $need_point) {
                return false;
            }

            $chatroom_from_user->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'), 1);
        }

        if($need_b_minus == true) {
            $user_controller = new UsersController();
            $avablable_point = $user_controller->getAvailableUserPoint($chatroom_to_user->no);

            if($avablable_point < $need_point) {
                return false;
            }

            $chatroom_to_user->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'), 1);
        }

        $profit = config('constants.SEND_ENVELOP_PROFIT');
        if ($need_a_plus == true) {
            $chatroom_from_user->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'),  (-1)*(1-$profit));
        }

        if ($need_b_plus == true) {
            $chatroom_to_user->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'),  (-1)*(1-$profit));
        }

        return true;
    }

    public function doNotification(HttpRequest $request)
    {
        $oper = $request->input("oper");
        $title = $request->input('title');
        $type = $request->input('type');
        $from_id = $request->input('from_user_no');
        $to_id = $request->input('to_user_no');
        $content = $request->input('content');
        $data = $request->input('data');
        $is_read = $request->input('is_read');

        $id = $request->input("no");

        if ($data != null) {
            $data = json_decode($data);
        }

        $response = config('constants.ERROR_NO');

        if ($oper == 'add') {
            if ($title == null) {
                $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            }

            $ret = $this->pointForEnvelop($from_id, $to_id);
            if($ret == false) {
                $response = config('constants.ERROR_NOT_ENOUGH_POINT');
                return response()->json($response);
            }

            $response = $this->addNotification($type, $from_id, $to_id, $title, $content, $data);
        } else if ($oper == 'edit') {
            if ($id == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if ($content != null) {
                $update_data['content'] = $content;
            }
            if ($title != null) {
                $update_data['title'] = $title;
            }
            if ($is_read != null) {
                $update_data['is_read'] = $is_read;
            }

            $results = Notification::where('no', $id)->update($update_data);
        } else if ($oper == 'del') {
            if ($id == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            Notification::where('no', $id)->delete();
        }

        return response()->json($response);
    }


    public function sendEnvelop(HttpRequest $request)
    {
        $user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $content = $request->input('content');

        $results = User::where('no', $user_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user = $results[0];

        $results = User::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $to_user = $results[0];

        $data = [];

        // 알림끄기한 유저에게는 쪽지를 보낼수 없고, 리력도 남지 않도록 처리
        $user_relation = UserRelation::where('user_no', $to_user_no)->where('relation_user_no', $user_no)->first();

        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            return config('constants.ERROR_BLOCKED_USER');
        }

        $user_relation = UserRelation::where('user_no', $user_no)->where('relation_user_no', $to_user_no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            return config('constants.ERROR_BLOCK_USER');
        }

        $ret = $this->pointForEnvelop($user_no, $to_user_no);
        if($ret == false) {
            $response = config('constants.ERROR_NOT_ENOUGH_POINT');
            return response()->json($response);
        }

        $data['content'] = $content;
        $ret = $this->sendAlarmMessage($from_user->no, $to_user->no, config('constants.NOTI_TYPE_SEND_ENVELOP'), $data);
        return response()->json($ret);
    }

    public function sendGroupEnvelop(HttpRequest $request)
    {
        $user_no = $request->input('from_user_no');
        $sex = $request->input('sex');
        $order = $request->input('order');
        $content = $request->input('content');
        $cur_lat = $request->input('latitude');
        $cur_lng = $request->input('longitude');

        $count = config('constants.NOTI_GROUP_LIMIT');

        if ($order == 0 && ($cur_lat == null || $cur_lng == null)) {  // order by distancer
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if ($user_no == null || $sex == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('no', $user_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user = $results[0];
        $from_user->fillInfo();

        // 유저를 차단한 유저와 유저가 차단한 유저들을 제외하기
        $disable_results = UserRelation::where(function ($q) use ($user_no) {
                                $q->where(function ($q) use ($user_no) {
                                    $q->where('user_no', $user_no)->where('is_alarm', config('constants.DISABLE'));
                                })
                                ->orWhere(function ($q) use ($user_no) {
                                    $q->where('relation_user_no', $user_no)->where('is_alarm', config('constants.DISABLE'));
                                });
                            })->get();
        $disable_array = [];
        for ($i = 0; $i < count($disable_results); $i++) {
            $disable_user = $disable_results[$i];
            if($disable_user->user_no == $user_no) {
                array_push($disable_array, $disable_user->relation_user_no);
            }
            else {
                array_push($disable_array, $disable_user->user_no);
            }
        }

        // DB::enableQueryLog();

        $query = DB::table('t_user')->select('t_user.no')->where('sex', $sex)->where('no', '!=', $user_no)->where('admin_level', config('constants.NO_ADMIN'));
        if(count($disable_array) > 0) {
            $query->whereNotIn('no', $disable_array);
        }

        if ($order == 0) { // distance
            $dist = DB::raw('(ROUND(6371 * ACOS(COS(RADIANS(' . $cur_lat . ')) * COS(RADIANS(t_user.latitude)) * COS(RADIANS(t_user.longitude) - RADIANS(' . $cur_lng . ')) + SIN(RADIANS(' . $cur_lat . ')) * SIN(RADIANS(t_user.latitude))),2))');
            $query = $query->orderBy($dist);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }

        $results = $query->offset(0)->limit($count)->get();

        //dd(DB::getQueryLog());

        $data = [];
        $data['content'] = $content;

        $success_cnt = count($results);
        $pointRule = config('constants.POINT_ADD_RULE');
        $need_point = $pointRule[config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE')] * $success_cnt;
        $user_controller = new UsersController();
        $user_enable_point = $user_controller->getAvailableUserPoint($user_no);

        if($user_enable_point < $need_point) {
            return config('constants.ERROR_NOT_ENOUGH_POINT');
        }

        for ($i = 0; $i < count($results); $i++) {
            $to_user_no = $results[$i]->no;
            $this->sendAlarmMessage($from_user->no, $to_user_no, config('constants.NOTI_TYPE_SEND_ENVELOP'), $data);

            $to_user = User::where('no', $to_user_no)->get();
            if($to_user != null) {
                $this->pointForEnvelop($from_user->no, $to_user_no);
            }
        }

        $results = User::where('no', $from_user->no)->get();
        $from_user = $results[0];
        return response()->json($from_user);
    }

    public function readEnvelop(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $from_user_no = $request->input('from_user_no');
        $search_type = $request->input('search_type');

        if ($user_no == null || ($from_user_no == null && $search_type == null)) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $update_data = [];
        $update_data['is_read'] = 1;

        if ($from_user_no != null && $from_user_no != -1) {  // read with to_user_no
            $query = Notification::where('from_user_no', $from_user_no)->where('to_user_no', $user_no);

            $query->update($update_data);
        } else if ($search_type != null) {
            if ($search_type == -1) { // read all
                Notification::where('to_user_no', $user_no)->update($update_data);
            } else if ($search_type == -2) { // read friend all
                $sql = '(to_user_no = ' . $user_no . ' and from_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1))';
                Notification::where(DB::raw($sql))->update($update_data);
            }
        }

        $response = config('constants.ERROR_NO');
        return response()->json($response);
    }

    public function deleteEnvelop(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $to_user_no = $request->input('to_user_no');
        $search_type = $request->input('search_type');

        if ($user_no == null || ($to_user_no == null && $search_type == null)) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if ($to_user_no != null) {  // read all
            Notification::where(function ($q) use ($user_no, $to_user_no) {
                $q->where(function ($q) use ($user_no, $to_user_no) {
                    $q->where('from_user_no', $user_no)->where('to_user_no', $to_user_no);
                })
                ->orWhere(function ($q) use ($user_no, $to_user_no) {
                    $q->where('to_user_no', $user_no)->where('from_user_no', $to_user_no);
                });
            })->delete();
        } else if ($search_type != null) {
            if ($search_type == -1) { // search_all
                Notification::where('to_user_no', $user_no)->orWhere('from_user_no', $user_no)->delete();
            } else if ($search_type == -2) { // search_friend
                $sql = ' (from_user_no = ' . $user_no . ' and to_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1))';
                $sql = $sql . ' OR (to_user_no = ' . $user_no . ' and from_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1))';
                Notification::where(DB::raw($sql))->delete();
            }
        }

        $response = config('constants.ERROR_NO');
        return response()->json($response);
    }

    public  function getUnReadCnt(HttpRequest $request) {
        $user_no = $request->input('user_no');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('no', $user_no)->first();
        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        // get unread cnt
        $unread_notification_cnt = $this->getUserUnreadCnt($user_no);

        $response = config('constants.ERROR_NO');
        $response['unread_cnt'] = $unread_notification_cnt;

        return response()->json($response);
    }
}