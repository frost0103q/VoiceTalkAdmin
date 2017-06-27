<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use App\Models\SSP;
use App\Models\Push;
use App\Models\Banner;
use App\Models\TalkNotice;
use App\Models\Message;
use DB;
use Request;
use Session;
use App\Models\ServerFile;

class NoticeController extends BasicController
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

    public function index()
    {
        $email = Session::get('u_email');
        if (!isset($email) || $email == null) {
            return redirect("/login");
        }

        return view('notice_mgr.index',['menu_index'=>5]);
    }

    //push
    public function ajax_push_table(HttpRequest $request){
        $table = 't_push';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $send_type = $request->input('send_type_search');
        if ($send_type)
            $custom_where .= " and send_type = $send_type";
        $title = $request->input('title_search');
        if ($title != "")
            $custom_where .= " and title like '%$title%'";
        $content = $request->input('content_search');
        if ($content != "")
            $custom_where .= " and content like '%$content%'";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'created_at', 'dt' => 1),
            array('db' => 'title', 'dt' => 2),
            array('db' => 'send_type', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.PUSH_SEND_MAIN'))
                        return trans('lang.main');
                    else if ($d == config('constants.PUSH_SEND_NOTICE'))
                        return trans('lang.notice_');
                    else if ($d == config('constants.PUSH_SEND_EVENT'))
                        return trans('lang.event');
                }),
            array('db' => 'content', 'dt' => 4),
            array('db' => 'img_url', 'dt' => 5),
            array('db' => 'no', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    $str = "<a title=".trans('lang.edit')." onclick='push_edit($d)' style='cursor:pointer'><i class='fa fa-edit'></i></a>&nbsp";
                    $str .= "<a title=".trans('lang.remove')." onclick='push_del($d)' style='cursor:pointer'><i class='fa fa-trash'></i></a>";
                    return $str;
                }),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => config('constants.DB_USER'),
            'pass' => config('constants.DB_PW'),
            'db' => config('constants.DB_NAME'),
            'host' => config('constants.DB_HOST')
        );

        return json_encode(
            SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $custom_where)
        );
    }

    public function add_push(HttpRequest $request)
    {
        $flag = $request->input('push_flag');
        if (!isset($flag))
            return config('constants.FAIL');
        $data["title"] = $request->input('push_title');
        $data["send_type"] = $request->input('push_send_type');
        $data["content"] = $request->input('push_content');
        $data["img_url"] = $request->input('push_img_url');

        if ($flag == config('constants.SAVE_FLAG_ADD')) {
            $data["created_at"] = date("Y-m-d H:i:s");
            $result = Push::insert($data);
            if (!$result)
                return config('constants.FAIL');
        } else if ($flag == config('constants.SAVE_FLAG_EDIT')) {
            $data["updated_at"] = date("Y-m-d H:i:s");
            $edit_id = $request->input('push_edit_id');
            $result = Push::where('no',$edit_id)->update($data);
            if (!$result)
                return config('constants.FAIL');
        }
        return config('constants.SUCCESS');
    }

    public function get_push_content(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = Push::where('no', $no)->first();
        return $response;
    }

    public function remove_push(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = Push::where('no', $no)->delete();
        if (!$response)
            return config('constants.FAIL');
        return config('constants.SUCCESS');
    }

    //banner

    public function ajax_banner_table(HttpRequest $request){
        $table = 't_banner';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $title = $request->input('title_search');
        if ($title != "")
            $custom_where .= " and title like '%$title%'";
        $content = $request->input('content_search');
        if ($content != "")
            $custom_where .= " and content like '%$content%'";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'created_at', 'dt' => 1),
            array('db' => 'title', 'dt' => 2),
            array('db' => 'content', 'dt' => 3),
            array('db' => 'img_url', 'dt' => 4),
            array('db' => 'no', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    $str = "<a title=".trans('lang.edit')." onclick='banner_edit($d)' style='cursor:pointer'><i class='fa fa-edit'></i></a>&nbsp";
                    $str .= "<a title=".trans('lang.remove')." onclick='banner_del($d)' style='cursor:pointer'><i class='fa fa-trash'></i></a>";
                    return $str;
                }),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => config('constants.DB_USER'),
            'pass' => config('constants.DB_PW'),
            'db' => config('constants.DB_NAME'),
            'host' => config('constants.DB_HOST')
        );

        return json_encode(
            SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $custom_where)
        );
    }

    public function add_banner(HttpRequest $request)
    {
        $flag = $request->input('banner_flag');
        if (!isset($flag))
            return config('constants.FAIL');
        $data["title"] = $request->input('banner_title');
        $data["content"] = $request->input('banner_content');
        $data["img_url"] = $request->input('banner_img_url');

        if ($flag == config('constants.SAVE_FLAG_ADD')) {
            $data["created_at"] = date("Y-m-d H:i:s");
            $result = Banner::insert($data);
            if (!$result)
                return config('constants.FAIL');
        } else if ($flag == config('constants.SAVE_FLAG_EDIT')) {
            $data["updated_at"] = date("Y-m-d H:i:s");
            $edit_id = $request->input('banner_edit_id');
            $result = Banner::where('no',$edit_id)->update($data);
            if (!$result)
                return config('constants.FAIL');
        }
        return config('constants.SUCCESS');
    }

    public function get_banner_content(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = Banner::where('no', $no)->first();
        return $response;
    }

    public function remove_banner(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = Banner::where('no', $no)->delete();
        if (!$response)
            return config('constants.FAIL');
        return config('constants.SUCCESS');
    }

    //talk

    public function ajax_talk_notice_table(HttpRequest $request){
        $table = 't_talk_notice';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sender_type = $request->input('$sender_type_search');
        if ($sender_type)
            $custom_where .= " and sender_type like '%$sender_type%'";
        $content = $request->input('content_search');
        if ($content != "")
            $custom_where .= " and content like '%$content%'";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'created_at', 'dt' => 1),
            array('db' => 'sender_type', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.TALK_ADMIN'))
                        return trans('lang.admin');
                    else if ($d == config('constants.TALK_POLICE'))
                        return trans('lang.talk_policy');
                }),
            array('db' => 'content', 'dt' => 3),
            array('db' => 'no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    $str = "<a title=".trans('lang.edit')." onclick='talk_edit($d)' style='cursor:pointer'><i class='fa fa-edit'></i></a>&nbsp";
                    $str .= "<a title=".trans('lang.remove')." onclick='talk_del($d)' style='cursor:pointer'><i class='fa fa-trash'></i></a>";
                    return $str;
                }),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => config('constants.DB_USER'),
            'pass' => config('constants.DB_PW'),
            'db' => config('constants.DB_NAME'),
            'host' => config('constants.DB_HOST')
        );

        return json_encode(
            SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $custom_where)
        );
    }

    public function add_talk(HttpRequest $request)
    {
        $flag = $request->input('talk_flag');
        if (!isset($flag))
            return config('constants.FAIL');
        $data["content"] = $request->input('talk_content');
        $data["sender_type"] = $request->input('talk_send_type');

        if ($flag == config('constants.SAVE_FLAG_ADD')) {
            $data["created_at"] = date("Y-m-d H:i:s");
            $result = TalkNotice::insert($data);
            if (!$result)
                return config('constants.FAIL');
        } else if ($flag == config('constants.SAVE_FLAG_EDIT')) {
            $data["updated_at"] = date("Y-m-d H:i:s");
            $edit_id = $request->input('talk_edit_id');
            $result = TalkNotice::where('no',$edit_id)->update($data);
            if (!$result)
                return config('constants.FAIL');
        }
        return config('constants.SUCCESS');
    }

    public function get_talk_content(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = TalkNotice::where('no', $no)->first();
        return $response;
    }

    public function remove_talk(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = TalkNotice::where('no', $no)->delete();
        if (!$response)
            return config('constants.FAIL');
        return config('constants.SUCCESS');
    }

    //message
    public function ajax_message_table(HttpRequest $request){
        $table = 't_message';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sender_type = $request->input('sender_type_search');
        if ($sender_type)
            $custom_where .= " and sender_type = $sender_type";
        $receive_type = $request->input('receive_type_search');
        if ($receive_type)
            $custom_where .= " and receive_type like '%$receive_type%'";
        $user_id = $request->input('user_id_search');
        if ($user_id != "")
            $custom_where .= " and user_id like '%$user_id%'";
        $sentence_type = $request->input('sentence_type_search');
        if ($sentence_type)
            $custom_where .= " and sentence_type like '%$sentence_type%'";
        $content = $request->input('content_search_search');
        if ($content != "")
            $custom_where .= " and content like '%$content%'";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'created_at', 'dt' => 1),
            array('db' => 'sender_type', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.TALK_ADMIN'))
                        return trans('lang.admin');
                    else if ($d == config('constants.TALK_POLICE'))
                        return trans('lang.talk_policy');
                }),
            array('db' => 'receive_type', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.SPECIAL_USER'))
                        return trans('lang.special_user');
                    else if ($d == config('constants.COMMON_USER'))
                        return trans('lang.common_user');
                    else if ($d == config('constants.TALK_USER'))
                        return trans('lang.talk_user');
                    else if ($d == config('constants.ALL_USER'))
                        return trans('lang.all_user');
                }),
            array('db' => 'user_id', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    return $d;
                }),
            array('db' => 'sentence_type', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.NO_PASSBOOK_GUIDE'))
                        return trans('lang.no_passbook_guide');
                    else if ($d == config('constants.LOST_PW'))
                        return trans('lang.lost_pw');
                    else if ($d == config('constants.DECLARE_RECEP'))
                        return trans('lang.declare_recep');
                }),
            array('db' => 'content', 'dt' => 6),
            array('db' => 'no', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    $str = "<a title=".trans('lang.edit')." onclick='message_edit($d)' style='cursor:pointer'><i class='fa fa-edit'></i></a>&nbsp";
                    $str .= "<a title=".trans('lang.remove')." onclick='message_del($d)' style='cursor:pointer'><i class='fa fa-trash'></i></a>";
                    return $str;
                }),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => config('constants.DB_USER'),
            'pass' => config('constants.DB_PW'),
            'db' => config('constants.DB_NAME'),
            'host' => config('constants.DB_HOST')
        );

        return json_encode(
            SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $custom_where)
        );
    }

    public function add_message(HttpRequest $request)
    {
        $flag = $request->input('message_flag');
        if (!isset($flag))
            return config('constants.FAIL');
        $data["sender_type"] = $request->input('message_sender_type');
        $data["receive_type"] = $request->input('message_receive_type');
        $data["user_id"] = $request->input('message_user_id');
        $data["sentence_type"] = $request->input('message_sentence_type');
        $data["content"] = $request->input('message_content');

        if ($flag == config('constants.SAVE_FLAG_ADD')) {
            $data["created_at"] = date("Y-m-d H:i:s");
            $result = message::insert($data);
            if (!$result)
                return config('constants.FAIL');
        } else if ($flag == config('constants.SAVE_FLAG_EDIT')) {
            $data["updated_at"] = date("Y-m-d H:i:s");
            $edit_id = $request->input('message_edit_id');
            $result = message::where('no',$edit_id)->update($data);
            if (!$result)
                return config('constants.FAIL');
        }
        return config('constants.SUCCESS');
    }

    public function get_message_content(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = message::where('no', $no)->first();
        return $response;
    }

    public function remove_message(HttpRequest $request)
    {
        $no = $request->input('no');
        if (!isset($no))
            return config('constants.FAIL');
        $response = message::where('no', $no)->delete();
        if (!$response)
            return config('constants.FAIL');
        return config('constants.SUCCESS');
    }
}
