<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use App\Models\AppUser;
use App\Models\Talk;
use App\Models\ServerFile;
use App\Models\TalkReview;
use App\Models\SSP;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;

use Config;
use DB;

class DeclareController extends BasicController
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


    public function ajax_declare_table(){
        $table = 'v_declare';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sex=$_POST['sex'];
        $user_no=$_POST['user_no'];
        $nickname=$_POST['nickname'];
        $phone_number=$_POST['phone_number'];
        $email=$_POST['email'];
        $chat_content=$_POST['chat_content'];

        if($sex!="")
            $custom_where.=" and from_user_sex=$sex";
        if($user_no!="")
            $custom_where.=" and from_user_no like '%".$user_no."%'";
        if($nickname!="")
            $custom_where.=" and from_user_nickname like '%".$nickname."%'";
        if($phone_number!="")
            $custom_where.=" and from_user_phone_number like '%".$phone_number."%'";
        if($email!="")
            $custom_where.=" and from_user_email like '%".$email."%'";
        if($chat_content!=""){
            $custom_where.=" and from_user_no in (select from_user_no from t_chathistory where content like '%".$chat_content."%') ";
        }

        $columns = array(
            array('db' => 'no', 'dt' => 0,
                'formatter'=>function($d,$row){
                    return '<input type="checkbox" class="declare_no" to_user_no="'.$row['to_user_no'].'" value="'.$d.'">';
                }
            ),
            array('db' => 'from_user_no', 'dt' => 1,
                'formatter'=>function($d,$row){
                    $results = User::where('no', $d)->first();
                    if($results!=null){
                        $verified=$results['verified'];
                        if($verified=='1')
                            return $results['nickname'].'&nbsp;&nbsp;<span class="badge badge-success">'.trans('lang.talk_insure').'</span>';
                        else
                            return $results['nickname'];
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'from_user_profile_img_path', 'dt' => 2),
            array('db' => 'to_user_no', 'dt' => 3,
                'formatter'=>function($d,$row){
                    $user_model = User::where('no', $d)->first();
                    if($user_model!=null){
                        $return_str = $user_model['nickname'];
                        if($user_model->force_stop_flag=='1'){
                            $return_str.='&nbsp;&nbsp;<span class="badge badge-success">'.trans('lang.force_stop').'</span>';
                        }
                        return $return_str.'<br>'.'<p style="color:#3598dc">P '.$user_model['point'].'</p>';
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'to_user_profile_img_path', 'dt' => 4),
            array('db' => 'content', 'dt' => 5),
            array('db' => 'created_at', 'dt' => 6),
            array('db' => 'updated_at', 'dt' => 7)
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
}
