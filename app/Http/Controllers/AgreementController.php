<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;
use Request;
use App\Models\ServerFile;



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
        $user_profile_query="SELECT t_file.*,t_user.`no` AS user_no,t_user.nickname FROM t_user LEFT JOIN t_file ON t_user.img_no=t_file.`no` WHERE checked!=1 AND type=0";
        $user_profile_img=DB::select($user_profile_query);

        $profile_img_declare=array();
        $profile_img_diff_time=array();
        foreach ($user_profile_img as $profile_model){
            $no=$profile_model->user_no;
            $declare_cnt = DB::table('t_declare')
                ->where('to_user_no', $no)
                ->count('no');
            $profile_img_declare[$no]=$declare_cnt;

            $time=$profile_model->updated_at==null ? $profile_model->created_at:$profile_model->updated_at;
            $diff_time = $this->get_time_diff($time);
            $profile_img_diff_time[$no]=$diff_time;
        }


        $talk_img_query="SELECT A.*,t_user.nickname from (SELECT t_file.*,t_talk.user_no FROM t_talk LEFT JOIN t_file ON t_file.`no`=t_talk.img_no WHERE t_file.checked!=1 AND t_file.type=0) AS A LEFT JOIN t_user ON A.user_no=t_user.no";
        $talk_img=DB::select($talk_img_query);

        $talk_img_declare=array();
        $talk_img_diff_time=array();
        foreach ($talk_img as $talk_img_model){
            $no=$talk_img_model->user_no;
            $declare_cnt = DB::table('t_declare')
                ->where('to_user_no', $no)
                ->count('no');
            $talk_img_declare[$no]=$declare_cnt;

            $time=$talk_img_model->updated_at==null ? $talk_img_model->created_at:$talk_img_model->updated_at;
            $diff_time = $this->get_time_diff($time);
            $talk_img_diff_time[$no]=$diff_time;
        }
        

        return view('photo_agree.index',
            ['menu_index'=>1,
            'user_profile_img'=>$user_profile_img,
            'talk_img'=>$talk_img,
            'profile_img_declare'=>$profile_img_declare,
            'talk_img_declare'=>$talk_img_declare,
            'profile_img_diff_time'=>$profile_img_diff_time,
            'talk_img_diff_time'=>$talk_img_diff_time]);
    }
    
    public function get_time_diff($time){
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

    public function img_agree(){

        $params = Request::all();
        $no = $params['t_file_no'];

        if(!isset($no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $no)->update(['checked' => 1]);
        if(!$results)
            return config('constants.FAIL');
        else
            return config('constants.SUCCESS');
    }

    public function img_disagree(){
        $params = Request::all();
        $no = $params['t_file_no'];

        if(!isset($no))
            return config('constants.FAIL');

        $results = ServerFile::where('no', $no)->update(['checked' => 0]);
        if(!$results)
            return config('constants.FAIL');
        else
            return config('constants.SUCCESS');
    }

    public function all_img_agree(){
        $params = Request::all();
        $img_no_array = $params['img_no_array'];

        if(!isset($img_no_array))
            return config('constants.FAIL');

        $img_no_array=explode(',',$img_no_array);

        for ($i=0;$i<count($img_no_array);$i++){
            $results = ServerFile::where('no', $img_no_array[$i])->update(['checked' => 1]);
            if(!$results)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }
}

