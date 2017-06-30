<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;
use Request;
use Session;
use App\Models\ServerFile;
use App\Models\SSP;

class StatisticController extends BasicController
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

        $data['menu_index']=7;
        $data['end_dt']=date('Y-m-d');
        $data['start_dt']=$this->getChangeDate(date('Y-m-d'),-6);

        return view('statistic.index',$data);
    }
    
    public function ajax_edwards_table(){
        $table = 't_edwards_ad';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $start_dt = $_POST['start_dt'];
        $end_dt = $_POST['end_dt'];
        $sex = $_POST['sex'];
        $user_no = $_POST['user_no'];
        $nickname = $_POST['nickname'];

        if ($start_dt != "")
            $custom_where .= " and created_at>='" . $start_dt . "'";
        if ($end_dt != "")
            $custom_where .= " and created_at<'" . $this->getChangeDate($end_dt, 1) . "'";
        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%'";
        if ($nickname != "")
            $custom_where .= " and user_no in (select no from t_user where nickname like '%" . $nickname . "%') ";
        if ($sex != "-1")
            $custom_where .= " and user_no in (select no from t_user where sex='".$sex."') ";


        global $total_point;
        $total_money = DB::select('SELECT sum(point) as total from t_edwards_ad WHERE ' . $custom_where);
        if ($total_money != null)
            $total_point = $total_money[0]->total;
        else
            $total_point = 0;

        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'user_no', 'dt' => 1,
                'formatter'=>function($d,$row){
                    return sprintf("%'.05d", $d);
                }
            ),
            array('db' => 'user_no', 'dt' => 2,
                'formatter'=>function($d,$row){
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if($user_model!=null){
                        $html='<span class="primary-link">'.$user_model->nickname.'('.$user_model->age.')'.'</span><br><span>&nbsp;'.$user_model->point.'P</span>';
                        return $html;
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'user_no', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if($user_model!=null){
                        $results = ServerFile::where('no', $user_model->img_no)->first();
                        if($results!=null)
                            return $results['path'];
                        else
                            return '';
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'point', 'dt' => 4),
            array('db' => 'jehu', 'dt' => 5),
            array('db' => 'ad_name', 'dt' => 6),
            array('db' => 'created_at', 'dt' => 7),
            array('db' => 'no', 'dt' => 8,
                'formatter' => function ($d, $row) {
                    global $total_point;
                    return $total_point;
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

    public function ajax_connect_table(){

        $start_dt=$_POST['start_dt'];
        $end_dt=$_POST['end_dt'];
        $sex=$_POST['sex'];
        $display_length=$_POST['display_length'];
        $start_record=$_POST['start'];

        global $custom_where;
        $custom_where=" 1=1 ";
        if($sex!="-1")
            $custom_where .= " and user_no in (select no from t_user where sex='" . $sex . "') ";

        if($end_dt=="")
            $end_dt=date('Y-m-d');
        if($start_dt=="")
            $start_dt=$this->getChangeDate($end_dt,-6);

        $total_cnt = $this->getDayCount($start_dt,$end_dt)+1;

        $columns = array();
        for ($i = 0;$i<$total_cnt;$i++){
            $date = $this->getChangeDate($start_dt,$i);

            $columns[] = array(
                array( 'db' => $date, 'dt' => 0),
                array( 'db' => $date,
                    'dt' => 1,
                    'formatter' => function($d) {
                        $d = $d[0];
                        global $custom_where;
                        return $this->get_login_cnt_date($d,$custom_where);
                    }
                ),
                array( 'db' => $date,
                    'dt' => 2,
                    'formatter' => function($d) {
                        $d = $d[0];
                        global $custom_where;
                        return $this->get_cash_cnt_date($d,$custom_where);
                    }
                ),
                array( 'db' => $date,
                    'dt' => 3,
                    'formatter' => function($d) {
                        $d = $d[0];
                    }
                ),
                array( 'db' => $date,
                    'dt' => 4,
                    'formatter' => function($d) {
                        $d = $d[0];
                        global $custom_where;
                        return $this->get_ad_point_date($d,$custom_where);
                    }
                ),
                array( 'db' => $date,
                    'dt' => 5,
                    'formatter' => function($d) {
                        $d = $d[0];
                    }
                ),
                array( 'db' => $date,
                    'dt' => 6,
                    'formatter' => function($d) {
                        $d = $d[0];
                    }
                ),
                array( 'db' => $date,
                    'dt' => 7,
                    'formatter' => function($d) {
                        $d = $d[0];
                        $male_cnt=$this->get_male_reg_cnt_date($d);
                        $female_cnt=$this->get_female_reg_cnt_date($d);
                        return $male_cnt.'+'.$female_cnt.'='.($male_cnt+$female_cnt);
                    }
                ),
                array( 'db' => $date,
                    'dt' => 8,
                    'formatter' => function($d) {
                        $d = $d[0];
                        global $custom_where;
                        return $this->get_present_point_date($d,$custom_where);
                    }
                )
            );
        }

        echo json_encode(
            SSP::simple1( $_POST, $columns, $total_cnt, $display_length, $start_record)
        );
    }

    public function get_login_cnt_date($date,$custom_where){

        $result = DB::select("SELECT COUNT(*) as cnt from t_login_history WHERE created_at like '%".$date."%' and " . $custom_where);

        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;

    }

    public function get_cash_cnt_date($date,$custom_where){

        $result = DB::select("SELECT COUNT(*) as cnt from t_cash_history WHERE cash_date like '%".$date."%' and " . $custom_where);

        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;

    }

    public function get_present_point_date($date,$custom_where){

        $result = DB::select("SELECT SUM(point) as total from t_pointhistory WHERE type=2 AND created_at like '%".$date."%' and " . $custom_where);

        if ($result != null)
            return abs($result[0]->total);
        else
            return 0;

    }

    public function get_ad_point_date($date,$custom_where){

        $result = DB::select("SELECT SUM(point) as total from t_edwards_ad WHERE  created_at like '%".$date."%' and " . $custom_where);

        if ($result != null)
            return abs($result[0]->total);
        else
            return 0;

    }

    public function get_male_reg_cnt_date($date){

        $result = DB::select("SELECT COUNT(*) as cnt from t_user WHERE created_at like '%".$date."%' and sex='".config('constants.MALE')."'");

        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;

    }

    public function get_female_reg_cnt_date($date){

        $result = DB::select("SELECT COUNT(*) as cnt from t_user WHERE created_at like '%".$date."%' and sex='".config('constants.FEMALE')."'");

        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;

    }
}
