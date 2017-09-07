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

    public function getNotificationList($search_type, $read_type, $user_no, $limit, $page)
    {

        $sql = "select * , (
                                SELECT
                                    count(*)
                                FROM
                                    t_notification d
                                WHERE
                                    d.is_read = 0
                                AND c.from_user_no = d.from_user_no
                                AND c.to_user_no = d.to_user_no
                              ) AS unread_cnt FROM ";

        if ($user_no != null) {
            $sql = $sql . "(
                            SELECT
                                *
                            FROM
                                t_notification a
                            INNER JOIN (
                                SELECT
                                    max(created_militime) latest
                                FROM
                                    t_notification
                                WHERE
                                    from_user_no = " . $user_no . "
                                OR to_user_no = " . $user_no . "
                                GROUP BY
                                    from_user_no
                            ) b ON a.created_militime = b.latest
                        ) c";
        } else {
            $sql = $sql . "(
                            SELECT
                                *
                            FROM
                                t_notification a
                            INNER JOIN (
                                SELECT
                                    max(created_militime) latest
                                FROM
                                    t_notification
                                GROUP BY
                                    from_user_no
                                ) b ON a.created_militime = b.latest
                            ) c";
        }

        $include_where = false;
        if ($search_type == -1) { // search_all
            if ($user_no != null) {
                $sql = $sql . " where (c.to_user_no =" . $user_no . " OR c.from_user_no=" . $user_no . ")";
                $include_where = true;
            }
        } else if ($search_type == -2 && $user_no != null) { // search_friend
            $sql = $sql . ' where ((c.from_user_no = ' . $user_no . ' and c.to_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1 AND t_user_relation.is_block_friend = 0))';
            $sql = $sql . '       OR (c.to_user_no = ' . $user_no . ' and c.from_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1 AND t_user_relation.is_block_friend = 0)))';
            $include_where = true;
        }

        if ($read_type == 0 || $read_type == 1) { // unread
            if ($include_where == false) {
                $sql = $sql . " where c.is_read =" . $read_type;
            } else {
                $sql = $sql . " AND c.is_read =" . $read_type;
            }

            if ($user_no != null) {
                $sql = $sql . " AND c.to_user_no=" . $user_no;
            }
        }

        $sql = $sql . " ORDER BY c.created_militime DESC";

        if ($page != null && $page > 0 && $limit != null && $limit >= 0) {
            $sql = $sql . " Limit " . $limit . " OFFSET " . ($limit * ($page - 1));
        }

        $response = DB::raw($sql);

        return DB::select($response);
    }

    public function getUserUnreadCnt($user_no)
    {
        $response = $this->getNotificationList(-1, 0, $user_no, -1, -1);
        $unread_cnt = 0;
        for ($i = 0; $i < count($response); $i++) {
            if ($response[$i]->from_user_no == $user_no) {
                $response[$i]->unread_cnt = 0;
            }
            $unread_cnt += $response[$i]->unread_cnt;
        }

        return $unread_cnt;
    }

    public function notificationList(HttpRequest $request)
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
        $read_type = $request->input('read_type') == null ? -1 : $request->input('read_type');

        $response = $this->getNotificationList($search_type, $read_type, $user_no, $limit, $page);

        for ($i = 0; $i < count($response); $i++) {
            $notification = $response[$i];
            if ($notification->from_user_no == $user_no) {
                $response[$i]->unread_cnt = 0;
                $user = User::where('no', $notification->to_user_no)->first();
                $user_relation = UserRelation::where('user_no', $user_no)->where('relation_user_no', $notification->to_user_no)->first();
            } else {
                $user = User::where('no', $notification->from_user_no)->first();
                $user_relation = UserRelation::where('user_no', $user_no)->where('relation_user_no', $notification->from_user_no)->first();
            }

            $user->fillInfo();
            $response[$i]->user = $user;
            $response[$i]->user_relation = $user_relation;
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
        $response = $response->where(function ($q) use ($from_user_no, $to_user_no) {
            $q->where(function ($q) use ($from_user_no, $to_user_no) {
                $q->where(['from_user_no' => $from_user_no, 'to_user_no' => $to_user_no]);
                $q->orWhere(['to_user_no' => $from_user_no, 'from_user_no' => $to_user_no]);
            })->where(function ($q) {

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

        $ret = $from_user->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'), 1);
        if ($ret == false) {
            return config('constants.ERROR_NOT_ENOUGH_POINT');
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

        $query = DB::table('t_user')->select('t_user.no')->where('sex', $sex)->where('no', '!=', $user_no)->where('admin_level', config('constants.NO_ADMIN'));

        if ($order == 0) { // distance
            $dist = DB::raw('(ROUND(6371 * ACOS(COS(RADIANS(' . $cur_lat . ')) * COS(RADIANS(t_user.latitude)) * COS(RADIANS(t_user.longitude) - RADIANS(' . $cur_lng . ')) + SIN(RADIANS(' . $cur_lat . ')) * SIN(RADIANS(t_user.latitude))),2))');
            $query = $query->orderBy($dist);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }

        $results = $query->offset(0)->limit($count)->get();

        $data = [];
        $data['content'] = $content;

        for ($i = 0; $i < count($results); $i++) {
            $to_user_no = $results[$i]->no;
            $this->sendAlarmMessage($from_user->no, $to_user_no, config('constants.NOTI_TYPE_SEND_ENVELOP'), $data);
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
                $sql = '(to_user_no = ' . $user_no . ' and from_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1 AND t_user_relation.is_block_friend = 0))';
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
                $sql = ' (from_user_no = ' . $user_no . ' and to_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1 AND t_user_relation.is_block_friend = 0))';
                $sql = $sql . ' OR (to_user_no = ' . $user_no . ' and from_user_no in (select t_user_relation.relation_user_no from t_user_relation where t_user_relation.user_no=' . $user_no . ' and t_user_relation.is_friend = 1 AND t_user_relation.is_block_friend = 0))';
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