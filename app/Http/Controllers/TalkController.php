<?php

namespace App\Http\Controllers;

use App\Models\ServerFile;
use App\Models\SSP;
use App\Models\Talk;
use App\Models\TalkReview;
use App\Models\User;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Session;

class TalkController extends BasicController
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

        return view('talk_user_mgr.index', ['menu_index' => 4]);
    }

    /**
     * getUserList
     *
     * @return json user array
     */
    public function talkList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $type = $request->input('type');
        $order = $request->input('order');
        $voice_type = $request->input('voice_type');
        $cur_lat = $request->input('latitude'); // 37.457087
        $cur_lng = $request->input('longitude'); // 126.705484
        $photo = $request->input('photo'); // 126.705484

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $query = Talk::select('t_talk.*')->where('t_talk.type', $type);
        if ($voice_type != 0) {
            $query = $query->where('t_talk.voice_type', $voice_type);
        }

        if ($photo == config('constants.TRUE')) {
            /*$query = $query->join('t_file', function ($q) {
                $q->on('t_talk.img_no', 't_file.no');
                $q->where('t_file.type', config('constants.IMAGE'));
                $q->where('t_file.checked', config('constants.AGREE'));
            });*/

            $users = User::select('t_user.no')->join('t_file', function ($q) {
                            $q->on('img_no', 't_file.no');
                            $q->where('t_file.type', config('constants.IMAGE'));
                            $q->where('t_file.checked', config('constants.AGREE'));
                        })->get();
            $query = $query->whereIn('t_talk.user_no', $users->toArray());
        }

        if ($order == config('constants.ORDER_DISTANCE')) { // Distance sort
            $dist = DB::raw('(ROUND(6371 * ACOS(COS(RADIANS(' . $cur_lat . ')) * COS(RADIANS(t_user.latitude)) * COS(RADIANS(t_user.longitude) - RADIANS(' . $cur_lng . ')) + SIN(RADIANS(' . $cur_lat . ')) * SIN(RADIANS(t_user.latitude))),2))');
            $query = $query->join('t_user', 't_talk.user_no', '=', 't_user.no')->orderBy($dist);
        } else if ($order == config('constants.ORDER_RANK')) { // Distance sort
            $review = DB::raw('(select SUM(mark) from t_consultingreview where t_consultingreview.to_user_no=t_talk.user_no)');
            $query = $query->orderBy($review, 'desc');
        } else {            // date
            $query = $query->orderBy('updated_at', 'desc');
        }

        $response = $query->offset($limit * ($page - 1))->limit($limit)->get();

        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->fillInfo();
        }

        return response()->json($response);
    }

    public function doTalk(HttpRequest $request)
    {
        $response = config('constants.ERROR_NO');

        $oper = $request->input("oper");
        $no = $request->input('no');
        $subject = $request->input('subject');
        $greeting = $request->input('greeting');
        $voice_type = $request->input('voice_type');
        $voice_file = $request->file('talk_voice');
        $photo_file = $request->file('talk_photo');
        $user_no = $request->input('user_no');
        $type = $request->input('type') == null ? config('constants.TALK_CONSULTING') : $request->input('type');
        $nick_name = $request->input('nickname');
        $age = $request->input('age');
        $delete_image = $request->input('del_img');

        $idiomCotroller = new IdiomController();

        if ($oper == 'add') {
            if ($type == config('constants.TALK_CONSULTING')) {
                if ($greeting == null || $voice_type == null || $user_no == null) {
                    $response = config('constants.ERROR_NO_PARMA');
                    return response()->json($response);
                }

                if ($idiomCotroller->includeForbidden($greeting) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }

                $user = User::where('no', $user_no)->first();

                if ($user == null) {
                    $response = config('constants.ERROR_NO_INFORMATION');
                    return response()->json($response);
                }


                // voice file upload
                if ($voice_file != null) {
                    $newfile = new ServerFile;
                    $voice_no = $newfile->uploadFile($voice_file, config('constants.VOICE'));
                } else {
                    $voice_no = -1;
                }

                // image upload
                if ($photo_file != null) {
                    $newfile = new ServerFile;
                    $photo_no = $newfile->uploadFile($photo_file, config('constants.IMAGE'));

                    if ($photo_no == null) {
                        $response = config('constants.ERROR_UPLOAD_FAILED');
                        return response()->json($response);
                    }

                    $user->img_no = $photo_no;
                    $user->save();
                }
                else {
                    if($delete_image == config('constants.TRUE')) {
                        $user->img_no = -1;
                        $user->save();
                    }
                }

                $talk = new Talk;

                $talk->subject = $subject;
                $talk->greeting = $greeting;
                $talk->voice_type = $voice_type;
                $talk->voice_no = $voice_no;
                $talk->user_no = $user_no;
                $talk->type = $type;

                $talk->save();

                $response['no'] = $talk->no;
            } else {
                if ($greeting == null || $user_no == null) {
                    $response = config('constants.ERROR_NO_PARMA');
                    return response()->json($response);
                }

                if ($idiomCotroller->includeForbidden($greeting) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }

                $user = User::where('no', $user_no)->first();

                if ($user == null) {
                    $response = config('constants.ERROR_NO_INFORMATION');
                    return response()->json($response);
                }

                // image upload
                if ($photo_file != null) {
                    $newfile = new ServerFile;
                    $photo_no = $newfile->uploadFile($photo_file, config('constants.IMAGE'));

                    if ($photo_no == null) {
                        $response = config('constants.ERROR_UPLOAD_FAILED');
                        return response()->json($response);
                    }

                    $user->img_no = $photo_no;
                    $user->save();
                }
                else {
                    if($delete_image == config('constants.TRUE')) {
                        $user->img_no = -1;
                        $user->save();
                    }
                }

                $talk = new Talk;

                $talk->greeting = $greeting;
                $talk->type = $type;
                $talk->user_no = $user_no;
                //$talk->img_no = $photo_no;

                $talk->save();
                $response['no'] = $talk->no;
            }
        } else if ($oper == 'edit') {
            if ($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if ($subject != null) {
                if ($idiomCotroller->includeForbidden($subject) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }
                $update_data['subject'] = $subject;
            }

            if ($greeting != null) {
                if ($idiomCotroller->includeForbidden($greeting) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }
                $update_data['greeting'] = $greeting;
            }

            if ($nick_name != null) {
                if ($idiomCotroller->includeForbidden($nick_name) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }
                $update_data['nickname'] = $nick_name;
            }


            if ($voice_type != null) {
                $update_data['voice_type'] = $voice_type;
            }

            if ($voice_file != null) {
                $newfile = new ServerFile;
                $voice_no = $newfile->uploadFile($voice_file, config('constants.VOICE'));

                if ($voice_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $update_data['voice_no'] = $voice_no;
            }


            if ($user_no != null) {
                $user = User::where('no', $user_no)->first();

                if ($user == null) {
                    $response = config('constants.ERROR_NO_INFORMATION');
                    return response()->json($response);
                }
                $update_data['user_no'] = $user_no;

                if ($photo_file != null) {
                    $newfile = new ServerFile;
                    $photo_no = $newfile->uploadFile($photo_file, config('constants.IMAGE'));

                    if ($photo_no == null) {
                        $response = config('constants.ERROR_UPLOAD_FAILED');
                        return response()->json($response);
                    }

                    //$update_data['img_no'] = $photo_no;
                    if($user_no == null) {
                        $talk = Talk::where('no', $no)->first();
                        $user = User::where('no', $talk->user_no)->first();
                    }

                    $user->img_no = $photo_no;
                    $user->save();
                }
                else {
                    if($delete_image == config('constants.TRUE')) {
                        $user->img_no = -1;
                        $user->save();
                    }
                }
            }

            if ($age != null) {
                $update_data['age'] = $age;
            }

            Talk::where('no', $no)->update($update_data);

        } else if ($oper == 'del') {
            if ($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            Talk::where('no', $no)->delete();
        } else {
            $response = config('constants.ERROR_NO_PARMA');
        }

        return response()->json($response);
    }

    public function talk(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $type = $request->input('type');

        if ($user_no == null || $type == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = Talk::where('user_no', $user_no)->where('type', $type)->get();

        if ($results == null || count($results) == 0) {
            $response["no"] = config('constants.INVALID_MODEL_NO');
            return response()->json($response);
        } else {
            $talk = $results[0];
            $talk->fillInfo();
        }

        return response()->json($talk);
    }


    public function duplicateTalk(HttpRequest $request)
    {
        $nickname = $request->input('nickname');

        if ($nickname == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');

        $nickname = $request->input('nickname');
        $results = Talk::where('nickname', $nickname)->get();

        if ($results != null && count($results) != 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        return response()->json($response);
    }

    public function ajax_talk_table()
    {
        $table = 'v_talk';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sex = $_POST['sex'];
        $user_no = $_POST['user_no'];
        $nickname = $_POST['nickname'];
        $phone_number = $_POST['phone_number'];
        $chat_content = $_POST['chat_content'];
        $talk_type = $_POST['talk_type'];

        if ($sex != "")
            $custom_where .= " and talk_user_sex=$sex";
        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%'";
        if ($nickname != "")
            $custom_where .= " and user_nickname like '%" . $nickname . "%'";
        if ($phone_number != "")
            $custom_where .= " and phone_number like '%" . $phone_number . "%'";
        if ($talk_type != "")
            $custom_where .= " and type =$talk_type";
        if ($chat_content != "")
            $custom_where .= " and greeting like '%" . $chat_content . "%'";

        $columns = array(
            array('db' => 'no', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    return '<input type="checkbox" class="talk_no" user_no="' . $row['user_no'] . '" value="' . $d . '">';
                }
            ),
            array('db' => 'user_no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null) {
                        $verified = $results['verified'];
                        if ($verified == '1')
                            return sprintf("%'.05d", $d) . '&nbsp;&nbsp;<span class="badge badge-success">' . trans('lang.talk_insure') . '</span>';
                        else
                            return sprintf("%'.05d", $d);
                    } else
                        return '';
                }
            ),
            array('db' => 'talk_img_path', 'dt' => 2),
            array('db' => 'user_nickname', 'dt' => 3),
            array('db' => 'greeting', 'dt' => 4),
            array('db' => 'type', 'dt' => 5,
                'formatter' =>function($d,$row){
                    if($d==config('constants.TOPIC_TALK'))
                        return trans('lang.topic_talk');
                    else
                        return trans('lang.nomal_talk');
                }
            ),
            array('db' => 'talk_edit_time', 'dt' => 6),
            array('db' => 'user_no', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null)
                        $reg_time = $user_model->created_at;
                    else
                        $reg_time = '';
                    $last_login_time = DB::table('t_login_history')->where('user_no', $d)->max('created_at');
                    return $reg_time . "/" . $last_login_time;
                }
            ),
            array('db' => 'profile_img_path', 'dt' => 8),
            array('db' => 'user_no', 'dt' => 9,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null) {
                        if ($user_model->force_stop_flag == '1') {
                            return '<span class="badge badge-success">' . trans('lang.force_stop') . '</span>';
                        }
                    } else
                        return '';
                }
            ),
            array('db' => 'user_no', 'dt' => 10,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null) {
                        if ($user_model->app_stop_flag == '1') {
                            return $user_model->app_stop_from_date . '~' . $user_model->app_stop_to_date;
                        }
                    } else
                        return '';
                }
            )
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

    public function del_selected_talk_img()
    {
        $selected_talk_str = $_POST['selected_talk_str'];
        $selected_talk_str_array = explode(',', $selected_talk_str);

        for ($i = 0; $i < count($selected_talk_str_array); $i++) {

            $talk_model = DB::table('t_talk')->where('no', $selected_talk_str_array[$i])->first();
            $img_no = $talk_model->img_no;
            DB::table('t_file')->where('no', $img_no)->delete();

            $result = DB::update('update t_talk set img_no = ?, updated_at = ? where no = ?', [-1, date('Y-m-d H:i:s'), $selected_talk_str_array[$i]]);
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function selected_talk_delete()
    {
        $selected_talk_str = $_POST['selected_talk_str'];
        $selected_talk_str_array = explode(',', $selected_talk_str);

        for ($i = 0; $i < count($selected_talk_str_array); $i++) {

            $result=DB::table('t_talk')->where('no', $selected_talk_str_array[$i])->delete();
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function del_selected_user_talk()
    {

        $selected_user_str = $_POST['selected_user_str'];
        $selected_user_array = explode(',', $selected_user_str);

        $user_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $user_array))
                array_push($user_array, $item);
        }

        for ($i = 0; $i < count($user_array); $i++) {

            $result = DB::delete('delete from t_talk where user_no = ?', [$user_array[$i]]);
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }
}
