<?php

namespace App\Http\Controllers;

use App\Models\ServerFile;
use App\Models\Talk;
use App\Models\User;
use DB;
use Request;
use Session;


class AgreementController extends BasicController
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

    public function agree_photo()
    {

        $email = Session::get('u_email');
        if (!isset($email) || $email == null) {
            return redirect("/login");
        }

        $user_profile_query = "SELECT * FROM  v_profile_file order by updated_at DESC";
        $user_profile_img = DB::select($user_profile_query);

        $profile_img_diff_time = array();
        foreach ($user_profile_img as $profile_model) {
            $time = ($profile_model->updated_at == null || $profile_model->updated_at == '0000-00-00 00:00:00') ? $profile_model->created_at : $profile_model->updated_at;
            $profile_img_diff_time[$profile_model->no] = $this->get_time_diff($time);
        }

        $talk_img_query = "SELECT * FROM v_talk_file where type='0'";
        $talk_img = DB::select($talk_img_query);

        $talk_img_diff_time = array();
        foreach ($talk_img as $talk_img_model) {
            $time = ($talk_img_model->updated_at == null || $talk_img_model->updated_at == '0000-00-00 00:00:00') ? $talk_img_model->created_at : $talk_img_model->updated_at;
            $talk_img_diff_time[$talk_img_model->no] = $this->get_time_diff($time);
        }


        return view('photo_agree.index',
            ['menu_index' => 1,
                'user_profile_img' => $user_profile_img,
                'talk_img' => $talk_img,
                'profile_img_diff_time' => $profile_img_diff_time,
                'talk_img_diff_time' => $talk_img_diff_time]);
    }

    public function get_time_diff($time)
    {
        $year = substr($time, 0, 4);
        $month = substr($time, 5, 2);
        $day = substr($time, 8, 2);
        $hour = substr($time, 11, 2);
        $minute = substr($time, 14, 2);
        $second = substr($time, 17);

        $now_time = date("Y-m-d H:i:s");
        $now_year = substr($now_time, 0, 4);
        $now_month = substr($now_time, 5, 2);
        $now_day = substr($now_time, 8, 2);
        $now_hour = substr($now_time, 11, 2);
        $now_minute = substr($now_time, 14, 2);
        $now_second = substr($now_time, 17);

        $minute_ago = date("Y-m-d H:i:s", mktime($hour + 1, $minute, $second, $month, $day, $year));
        $hour_ago = date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day + 1, $year));
        $week_ago = date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day + 7, $year));
        $four_week_ago = date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day + 28, $year));
        $next_year = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, $year + 1));

        $old_time_second = strtotime($time);
        $current_time_second = strtotime($now_time);
        $result_data = "";

        $status = true;

        if ($now_time >= $next_year) {
            $result_data = $year . "." . $month . "." . $day;
            switch (date("N", mktime($hour + 1, $minute, $second, $month, $day, $year))) {
                case 1:
                    $result_data .= "(월) ";
                    break;
                case 2:
                    $result_data .= "(화) ";
                    break;
                case 3:
                    $result_data .= "(수) ";
                    break;
                case 4:
                    $result_data .= "(목) ";
                    break;
                case 5:
                    $result_data .= "(금) ";
                    break;
                case 6:
                    $result_data .= "(토) ";
                    break;
                case 7:
                    $result_data .= "(일) ";
                    break;
            }

            if ($hour < 12) {
                $result_data .= "오전 " . $hour . ":" . $minute;
            } else {
                $result_data .= "오후 " . ($hour - 12) . ":" . $minute;
            }

            $status = false;

        }

        if ($status && $now_time >= $four_week_ago && $time < $next_year) {
            $result_data = $month . "." . $day;
            switch (date("N", mktime($hour + 1, $minute, $second, $month, $day, $year))) {
                case 1:
                    $result_data .= "(월) ";
                    break;
                case 2:
                    $result_data .= "(화) ";
                    break;
                case 3:
                    $result_data .= "(수) ";
                    break;
                case 4:
                    $result_data .= "(목) ";
                    break;
                case 5:
                    $result_data .= "(금) ";
                    break;
                case 6:
                    $result_data .= "(토) ";
                    break;
                case 7:
                    $result_data .= "(일) ";
                    break;
            }

            if ($hour < 12) {
                $result_data .= "오전 " . $hour . ":" . $minute;
            } else {
                $result_data .= "오후 " . ($hour - 12) . ":" . $minute;
            }

            $status = false;
        }

        if ($status && $now_time >= $week_ago && $now_time < $four_week_ago) {
            $result_data = ceil(($current_time_second - $old_time_second) / (3600 * 24 * 7)) . "주 전";
            $status = false;
        }

        if ($status && $now_time >= $hour_ago && $now_time < $week_ago) {
            $result_data = floor(($current_time_second - $old_time_second) / (3600 * 24)) . "일 전";
            $status = false;
        }

        if ($status && $now_time >= $minute_ago && $now_time < $hour_ago) {
            $result_data = ceil(($current_time_second - $old_time_second) / (60 * 60)) . "시간 전";
            $status = false;
        }

        if ($status && $now_time < $minute_ago) {
            $result_data = ceil(($current_time_second - $old_time_second) / 60) . "분 전";
            $status = false;
        }
        return $result_data;
    }

    public function get_declare_cnt($user_no)
    {
        $declare_cnt = DB::table('t_declare')
            ->where('to_user_no', $user_no)
            ->count('no');
        return $declare_cnt;
    }

    public function img_agree()
    {

        $params = Request::all();
        $no = $params['t_file_no'];
        $type = $params['type'];

        if (!isset($no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $no)->update(['checked' => config('constants.AGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
        if (!$results)
            return config('constants.FAIL');
        else {
            // send Admin Push
            $admin_no = Session::get('u_no');
            $user = User::where('img_no', $no)->first();
            $user_no = $user->no;

            $data = [];
            $data['type'] = $type;

            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_IMAGE_AGREE'), $data);

            return $this->get_img_html($type, $no);
        }
    }

    public function img_disagree()
    {
        $params = Request::all();
        $no = $params['t_file_no'];
        $type = $params['type'];

        if (!isset($no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $no)->update(['checked' => config('constants.DISAGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
        if (!$results)
            return config('constants.FAIL');
        else {

            // send Admin Push
            $admin_no = Session::get('u_no');
            $user = User::where('img_no', $no)->first();
            $user_no = $user->no;

            $data = [];
            $data['type'] = $type;
            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_REFUSE_IMAGE'), $data);

            return $this->get_img_html($type, $no);
        }
    }

    public function all_img_agree()
    {
        $params = Request::all();
        $img_no_array = $params['img_no_array'];

        if (!isset($img_no_array))
            return config('constants.FAIL');

        $img_no_array = explode(',', $img_no_array);

        $new_selected_img_array = array();

        foreach ($img_no_array as $item) {
            if (!in_array($item, $new_selected_img_array))
                array_push($new_selected_img_array, $item);
        }

        for ($i = 0; $i < count($new_selected_img_array); $i++) {
            $results = ServerFile::where('no', $new_selected_img_array[$i])->update(['checked' => config('constants.AGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
            if (!$results)
                return config('constants.FAIL');

            // send Admin Push
            $admin_no = Session::get('u_no');
            $user = User::where('img_no', $new_selected_img_array[$i])->first();
            $user_no = $user->no;

            $data = [];
            $data['type'] = 'user';

            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_IMAGE_AGREE'), $data);
        }

        return config('constants.SUCCESS');
    }

    public function get_user_data()
    {
        $no = $_POST["no"];
        if (!isset($no))
            return (config('constants.FAIL'));
        $query = DB::select("select * from t_user where no = ?", [$no]);
        if (count($query) < 1)
            return (config('constants.FAIL'));
        $img = DB::select("select * from t_file where no = ?", [$query[0]->img_no]);
        if (count($img) < 1)
            $img_path = "";
        else
            $img_path = $img[0]->path;
        return (json_encode(array('info' => $query[0], 'path' => $img_path)));
    }

    public function talk_confirm()
    {
        $user_no = $_POST["user_no"];
        if (!isset($user_no))
            return (config('constants.FAIL'));
        $query = DB::select("select * from t_talk where user_no = ?", [$user_no]);
        if (count($query) < 1)
            return (config('constants.FAIL'));
        $img = DB::select("select * from t_file where no = ?", [$query[0]->img_no]);
        if (count($img) < 1)
            $img_path = "";
        else
            $img_path = $img[0]->path;

        $voice = DB::select("select * from t_file where no = ?", [$query[0]->voice_no]);
        if (count($voice) < 1)
            $voice_path = "";
        else
            $voice_path = $voice[0]->path;

        return (json_encode(array('info' => $query[0], 'img_path' => $img_path, 'voice_path' => $voice_path)));
    }

    public function get_img_html($type, $no)
    {
        if ($type == 'talk') {
            $talk_img_query = "SELECT * FROM v_talk_file where type='0' and `no`='" . $no . "'";
            $talk_img = DB::select($talk_img_query);
            $img_model = $talk_img[0];

            $time = ($img_model->updated_at == null || $img_model->updated_at == '0000-00-00 00:00:00') ? $img_model->created_at : $img_model->updated_at;
            $talk_img_diff_time[$img_model->no] = $this->get_time_diff($time);

            return view('photo_agree.img',
                ['img_model' => $img_model,
                    'type' => $type,
                    'all_flag' => false,
                    'talk_img_diff_time' => $talk_img_diff_time]);
        } else {
            $user_profile_query = "SELECT * FROM  v_profile_file WHERE `no`='" . $no . "'";
            $user_profile_img = DB::select($user_profile_query);
            $img_model = $user_profile_img[0];

            $time = ($img_model->updated_at == null || $img_model->updated_at == '0000-00-00 00:00:00') ? $img_model->created_at : $img_model->updated_at;
            $profile_img_diff_time[$img_model->no] = $this->get_time_diff($time);

            return view('photo_agree.img',
                ['img_model' => $img_model,
                    'type' => $type,
                    'all_flag' => false,
                    'profile_img_diff_time' => $profile_img_diff_time]);
        }
    }

    public function agree_voice()
    {

        $email = Session::get('u_email');
        if (!isset($email) || $email == null) {
            return redirect("/login");
        }

        $voice_query = "SELECT * FROM v_talk_file where type='1' order by updated_at DESC";
        $talk_voice = DB::select($voice_query);

        $talk_voice_diff_time = array();
        foreach ($talk_voice as $model) {
            $time = ($model->updated_at == null || $model->updated_at == '0000-00-00 00:00:00') ? $model->created_at : $model->updated_at;
            $talk_voice_diff_time[$model->no] = $this->get_time_diff($time);
        }


        return view('voice_agree.index',
            ['menu_index' => 2,
                'talk_voice' => $talk_voice,
                'talk_voice_diff_time' => $talk_voice_diff_time,
            ]);
    }

    public function get_voice_html($talk_no)
    {
        $voice_query = "SELECT * FROM v_talk_file where type='1' and talk_no='" . $talk_no . "'";
        $talk_voice = DB::select($voice_query);

        $talk_voice_diff_time = array();
        foreach ($talk_voice as $model) {
            $time = ($model->updated_at == null || $model->updated_at == '0000-00-00 00:00:00') ? $model->created_at : $model->updated_at;
            $talk_voice_diff_time[$model->no] = $this->get_time_diff($time);
        }


        return view('voice_agree.voice',
            ['voice_model' => $talk_voice[0],
                'talk_voice_diff_time' => $talk_voice_diff_time,
            ]);
    }

    public function voice_agree()
    {

        $params = Request::all();
        $file_no = $params['t_file_no'];
        $talk_no = $params['talk_no'];

        if (!isset($file_no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $file_no)->update(['checked' => config('constants.AGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
        if (!$results)
            return config('constants.FAIL');
        else {
            // send Admin Push
            $admin_no = Session::get('u_no');
            $talk = Talk::where('voice_no', $file_no)->first();
            $user_no = $talk->user_no;

            $data = [];
            $data['type'] = 'talk';

            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_VOICE_AGREE'), $data);

            return $this->get_voice_html($talk_no);
        }
    }

    public function voice_disagree()
    {
        $params = Request::all();
        $file_no = $params['t_file_no'];
        $talk_no = $params['talk_no'];

        if (!isset($file_no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $file_no)->update(['checked' => config('constants.DISAGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
        if (!$results)
            return config('constants.FAIL');
        else {
            // send Admin Push
            $admin_no = Session::get('u_no');
            $talk = Talk::where('voice_no', $file_no)->first();
            $user_no = $talk->user_no;

            $data = [];
            $data['type'] = 'talk';

            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_VOICE_REFUSE'), $data);

            return $this->get_voice_html($talk_no);
        }
    }

    public function all_voice_agree()
    {
        $params = Request::all();
        $voice_no_array = $params['voice_no_array'];

        if (!isset($voice_no_array))
            return config('constants.FAIL');

        $voice_no_array = explode(',', $voice_no_array);

        $new_selected_voice_array = array();

        foreach ($voice_no_array as $item) {
            if (!in_array($item, $new_selected_voice_array))
                array_push($new_selected_voice_array, $item);
        }

        for ($i = 0; $i < count($new_selected_voice_array); $i++) {
            $results = ServerFile::where('no', $new_selected_voice_array[$i])->update(['checked' => config('constants.AGREE'), 'updated_at' => date('Y-m-d H:i:s')]);
            if (!$results)
                return config('constants.FAIL');

            $admin_no = Session::get('u_no');
            $talk = Talk::where('voice_no', $new_selected_voice_array[$i])->first();
            $user_no = $talk->user_no;

            $data = [];
            $data['type'] = 'talk';

            $this->sendAlarmMessage($admin_no, $user_no, config('constants.NOTI_TYPE_ADMIN_VOICE_AGREE'), $data);
        }

        return config('constants.SUCCESS');
    }
}

