<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;
use Request;
use Session;
use App\Models\SSP;
use App\Models\Admin;
use App\Models\ManageNotice;
use App\Models\Opinion;
use App\Models\ServerFile;
use App\Models\CashQuestion;
use App\Models\User;

class CashQuestionController extends BasicController
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

        return view('cash_question.index',['menu_index'=>3]);
    }
    
    public function ajax_cash_question_table(){
        $table = 't_cash_question';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'user_no', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter'=>function($d,$row){
                    $users = DB::select('SELECT t_file.path from t_user,t_file WHERE t_user.img_no=t_file.`no` and t_user.`no`=?', [$d]);
                    if($users!=null)
                        return $users[0]->path;
                    else
                        return '';
                }
            ),
            array('db' => 'user_no', 'dt' => 3,
                'formatter'=>function($d,$row){
                    $results = User::where('no', $d)->first();
                    if($results!=null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'content', 'dt' => 4),
            array('db' => 'created_at', 'dt' => 5),
            array('db' => 'answer', 'dt' => 6),
            array('db' => 'no', 'dt' => 7)
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

    public function save_cash_question_opinion(){
        $params = Request::all();
        $no = $params['no'];
        $data['answer'] = $params['answer'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $result=CashQuestion::where('no',$no)->update($data);
        if($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }
    
    public function delete_cash_questin(){
        $no=$_POST['no'];
        $result=CashQuestion::where('no',$no)->delete();
        if($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }
}
