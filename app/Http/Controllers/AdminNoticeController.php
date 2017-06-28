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

class AdminNoticeController extends BasicController
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

        return view('admin_notice.index',['menu_index'=>8]);
    }

    public function ajax_opinion_table(){

        $table = 't_opinion';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'title', 'dt' => 1),
            array('db' => 'writer', 'dt' => 2,
                'formatter'=>function($d,$row){
                    $results = Admin::where('no', $d)->first();
                    if($results!=null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'created_at', 'dt' => 3),
            array('db' => 'read_cnt', 'dt' => 4),
            array('db' => 'no', 'dt' => 5),
            array('db' => 'content', 'dt' => 6)
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

    public function save_opinion(){
        $params = Request::all();
        $no = $params['opinion_no'];

        $writer = Session::get('u_no');


        if($no==""){
            $data['title'] = $params['opinion_title'];
            $data['content'] = $params['opinion_content'];
            $data['writer'] = $writer;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['read_cnt'] = 0;

            $result=Opinion::insert($data);
            if($result)
                return config('constants.SUCCESS');
            else
                return config('constants.FAIL');
        }
        else{
            $data['title'] = $params['opinion_title'];
            $data['content'] = $params['opinion_content'];
            $data['writer'] = $writer;
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result=Opinion::where('no',$no)->update($data);
            if($result)
                return config('constants.SUCCESS');
            else
                return config('constants.FAIL');
        }
    }

    public function delete_opinion(){
        $opinion_no=$_POST['opinion_no'];
        $result=Opinion::where('no',$opinion_no)->delete();
        if($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }

    public function ajax_manage_notice_table(){
        $table = 't_manage_notice';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'title', 'dt' => 1),
            array('db' => 'file_url', 'dt' => 2),
            array('db' => 'writer', 'dt' => 3,
                'formatter'=>function($d,$row){
                    $results = Admin::where('no', $d)->first();
                    if($results!=null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'created_at', 'dt' => 4),
            array('db' => 'read_cnt', 'dt' => 5),
            array('db' => 'no', 'dt' => 6),
            array('db' => 'content', 'dt' => 7)
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

    public function save_manage_notice(){
        $params = Request::all();
        $no = $params['no'];

        $writer = Session::get('u_no');


        if($no==""){
            $data['title'] = $params['title'];
            $data['content'] = $params['content'];
            $data['file_url'] = $params['file_url'];
            $data['writer'] = $writer;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['read_cnt'] = 0;

            $result=ManageNotice::insert($data);
            if($result)
                return config('constants.SUCCESS');
            else
                return config('constants.FAIL');
        }
        else{
            $data['title'] = $params['title'];
            $data['content'] = $params['content'];
            $data['file_url'] = $params['file_url'];
            $data['writer'] = $writer;
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result=ManageNotice::where('no',$no)->update($data);
            if($result)
                return config('constants.SUCCESS');
            else
                return config('constants.FAIL');
        }
    }

    public function delete_manage_notice(){
        $manage_notice_no=$_POST['manage_notice_no'];
        $result=ManageNotice::where('no',$manage_notice_no)->delete();
        if($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }
}
