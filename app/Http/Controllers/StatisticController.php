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

        return view('statistic.index',['menu_index'=>7]);
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
}
