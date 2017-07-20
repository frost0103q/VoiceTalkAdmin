<?php

namespace App\Http\Controllers;

use App\Models\CashDeclare;
use App\Models\CashQuestion;
use App\Models\Notification;
use App\Models\SSP;
use App\Models\User;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Request;
use Session;

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

    /*
    |--------------------------------------------------------------------------
    | HomeController Controller Mobile API Functions
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    public function cashQuestionList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $response = CashQuestion::select('*');
        if ($user_no != null) {
            $response = $response->where('user_no', $user_no);
        }

        $response = $response->orderBy('updated_at', 'desc')->offset($limit * ($page - 1))->limit($limit)->get();

        return response()->json($response);
    }

    public function doCashQuestion(HttpRequest $request)
    {

        $oper = $request->input("oper");
        $content = $request->input("content");
        $user_no = $request->input('user_no');

        $response = config('constants.ERROR_NO');

        if ($oper == 'add') {
            if ($user_no == null || $content == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $question = new CashQuestion();
            $question->user_no = $user_no;
            $question->content = $content;
            $question->content = $content;

            $question->save();
            $response['no'] = $question->no;
        } else if ($oper == 'edit') {

            $no = $request->input('no');

            if ($no == null || ($user_no == null && $content == null)) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];

            if ($user_no != null) {
                $update_data['user_no'] = $user_no;
            }
            if ($content != null) {
                $update_data['content'] = $content;
            }

            CashQuestion::where('no', $no)->update($update_data);
        } else if ($oper == 'del') {
            $no = $request->input('no');
            if ($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            CashQuestion::where('no', $no)->delete();
        }

        return response()->json($response);
    }


    private function sendCashAnswerAlarm($cash_no, $admin_no)
    {
        $cash = CashQuestion::where('no', $cash_no)->first();

        if ($cash == null) {
            return;
        }

        $this->sendAlarmMessage($admin_no, $cash->user_no, config('constants.NOTI_TYPE_CASH_QA'));
    }


    /*
    |--------------------------------------------------------------------------
    | HomeController Controller Admin Functions
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function index()
    {
        $email = Session::get('u_email');
        if (!isset($email) || $email == null) {
            return redirect("/login");
        }

        return view('cash_question.index', ['menu_index' => 3]);
    }

    public function ajax_cash_question_table()
    {

        $params = Request::all();
        $user_sex = $params['user_sex'];
        $user_no = $params['user_no'];
        $user_nickname = $params['user_nickname'];
        $user_phone_number = $params['user_phone_number'];
        /*$user_email = $params['user_email'];*/
        $user_chat_content = $params['user_chat_content'];

        // Table's name
        $table = 't_cash_question';
        // Table's primary key
        $primaryKey = 'no';

        // Custom Where
        $custom_where = "1=1";

        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%' ";
        if ($user_sex != "-1")
            $custom_where .= " and user_no in (select no from t_user where sex='" . $user_sex . "') ";
        if ($user_nickname != "")
            $custom_where .= " and user_no in (select no from t_user where nickname like '%" . $user_nickname . "%') ";
        if ($user_phone_number != "")
            $custom_where .= " and user_no in (select no from t_user where phone_number like '%" . $user_phone_number . "%') ";
        /*if ($user_email != "")
            $custom_where .= " and user_no in (select no from t_user where email like '%" . $user_email . "%') ";*/
        if ($user_chat_content != "")
            $custom_where .= " and content like '%" . $user_chat_content . "%' ";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'user_no', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    $users = DB::select('SELECT t_file.path from t_user,t_file WHERE t_user.img_no=t_file.`no` and t_user.`no`=?', [$d]);
                    if ($users != null)
                        return $users[0]->path;
                    else
                        return '';
                }
            ),
            array('db' => 'user_no', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'content', 'dt' => 4),
            array('db' => 'created_at', 'dt' => 5),
            array('db' => 'answer', 'dt' => 6),
            array('db' => 'updated_at', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    if ($d != "" && $d != "0000-00-00 00:00:00" && $d != null)
                        return $d;
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 8)
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

    public function save_cash_question_opinion()
    {
        $params = Request::all();
        $no = $params['no'];
        $data['answer'] = $params['answer'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $result = CashQuestion::where('no', $no)->update($data);
        if ($result) {
            $this->sendCashAnswerAlarm($no, Session::get('u_no'));
            return config('constants.SUCCESS');
        } else
            return config('constants.FAIL');
    }


    public function delete_cash_questin()
    {
        $no = $_POST['no'];
        $result = CashQuestion::where('no', $no)->delete();
        if ($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }

    public function ajax_cash_declare_table()
    {
        $params = Request::all();
        $user_sex = $params['user_sex'];
        $user_no = $params['user_no'];
        $user_nickname = $params['user_nickname'];
        $user_phone_number = $params['user_phone_number'];
        $user_email = $params['user_email'];
        $user_chat_content = $params['user_chat_content'];

        // Table's name
        $table = 't_cash_declare';
        // Table's primary key
        $primaryKey = 'no';

        // Custom Where
        $custom_where = "1=1";

        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%' ";
        if ($user_sex != "-1")
            $custom_where .= " and user_no in (select no from t_user where sex='" . $user_sex . "') ";
        if ($user_nickname != "")
            $custom_where .= " and user_no in (select no from t_user where nickname like '%" . $user_nickname . "%') ";
        if ($user_phone_number != "")
            $custom_where .= " and user_no in (select no from t_user where phone_number like '%" . $user_phone_number . "%') ";
        if ($user_email != "")
            $custom_where .= " and user_no in (select no from t_user where email like '%" . $user_email . "%') ";
        if ($user_chat_content != "")
            $custom_where .= " and user_no in (select from_user_no from t_chathistory where content like '%" . $user_chat_content . "%') ";


        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'user_no', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'content', 'dt' => 3),
            array('db' => 'created_at', 'dt' => 4),
            array('db' => 'answer', 'dt' => 5),
            array('db' => 'updated_at', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    if ($d != "" && $d != "0000-00-00 00:00:00" && $d != null)
                        return $d;
                    else
                        return '';
                }
            ),
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

    public function save_cash_declare()
    {
        $params = Request::all();
        $no = $params['no'];
        $data['answer'] = $params['answer'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $result = CashDeclare::where('no', $no)->update($data);
        if ($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }

    public function delete_cash_declare()
    {
        $no = $_POST['no'];
        $result = CashDeclare::where('no', $no)->delete();
        if ($result)
            return config('constants.SUCCESS');
        else
            return config('constants.FAIL');
    }
}
