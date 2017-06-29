<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/3/2017
 * Time: 12:23 AM
 */

namespace App\Http\Controllers;


use App\Http\Controllers\BasicController;
use App\Models\Notification;
use App\Models\Withdraw;
use App\Models\AppUser;
use App\Models\ServerFile;
use App\Models\SSP;
use App\Models\User;
use App\Models\CashHistory;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;
use Redirect;
use Request;
use URL;
use Session;
use Socialite;
use Config;


class WithdrawController  extends BasicController
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

    /******************************************************************
     *
     * View Functions
     *
     ******************************************************************/

    public function index()
    {
        return view('withdraw.index', ["menu_index"=>6]);
    }



    /******************************************************************
     *
     * Private Functions
     *
     ******************************************************************/


    /******************************************************************
     *
     * Public Functions
     *
     ******************************************************************/
    private function testFunction() {

    }


    /******************************************************************
     *
     * API Functions
     *
     ******************************************************************/
    /**
     * getUserList
     *
     * @return json user arrya
     */
    public function withdrawList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if($page == null) {
            $params['page'] = 1;
        }

        $response = null;
        if($user_no != null) {
            $response = Withdraw::where('user_no', $user_no);
        }
        if($response != null) {
            $response = $response->offset($limit * ($page - 1))->limit($limit)->get();
        }
        else {
            $response = Withdraw::offset($limit * ($page - 1))->limit($limit)->get();
        }

        return response()->json($response);
    }

    public function doWithdraw(HttpRequest $request){

        $oper = $request->input("oper");
        $user_no = $request->input('user_no');
        $money = $request->input('money');
        $bank_name = $request->input('bank_name');
        $account_number = $request->input('account_number');
        $account_realname = $request->input('account_realname');
        $account_name = $request->input('account_name');
        $account_birth = $request->input('account_birth');

        $id = $request->input("no");

        $response = config('constants.ERROR_NO');

        if($oper == 'add') {
            if($user_no == null || $bank_name == null || $account_number == null || $account_realname == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $withdraw = new Withdraw();
            $withdraw->user_no = $user_no;
            $withdraw->money = $money;
            $withdraw->bank_name = $bank_name;
            $withdraw->account_number = $account_number;
            $withdraw->account_name = $account_name;
            $withdraw->account_realname = $account_realname;
            $withdraw->account_birth = $account_birth;

            $withdraw->save();
            $response['no'] = $withdraw->no;
        }
        else if($oper == 'edit') {
            if($id == null || ($account_realname == null && $account_birth == null)) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if($account_realname != null) {
                $update_data['account_realname'] = $account_realname;
            }
            if($account_birth != null) {
                $update_data['account_birth'] = $account_birth;
            }

            $results = Withdraw::where('no', $id)
                ->update($update_data);
        }
        else if($oper == 'del') {
            if($id == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            Withdraw::where('no', $id)->delete();
        }

        return response()->json($response);
    }

    public function ajax_cash_table(){
        $table = 't_cash_history';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $start_dt=$_POST['start_dt'];
        $end_dt=$_POST['end_dt'];
        $status=$_POST['status'];
        $user_no=$_POST['user_no'];
        $nickname=$_POST['nickname'];
        $order_number=$_POST['order_number'];
        $cash_code=$_POST['cash_code'];

        if($start_dt!="")
            $custom_where.=" and cash_date>='".$start_dt."'";
        if($end_dt!="")
            $custom_where.=" and cash_date<='".$this->getChangeDate($end_dt,1)."'";
        if($user_no!="")
            $custom_where.=" and user_no like '%".$user_no."%'";
        if($nickname!="")
            $custom_where.=" and user_no in (select no from t_user where nickname like '%".$nickname."%') ";
        if($status!="-1")
            $custom_where.=" and status ='".$status."'";
        if($order_number!="")
            $custom_where.=" and order_number like '%".$order_number."%'";
        if($cash_code!="")
            $custom_where.=" and cash_code like '%".$cash_code."%'";

        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'order_number', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter'=>function($d,$row){
                    $results = User::where('no', $d)->first();
                    if($results!=null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 3,
                'formatter'=>function($d,$row){
                    $results = CashHistory::where('no', $d)->first();
                    if($results!=null)
                        return $results['cash_amount'].' / Point '.$results['point'];
                    else
                        return '';
                }
            ),
            array('db' => 'cash_date', 'dt' => 4),
            array('db' => 'status', 'dt' => 5,
                'formatter'=>function($d,$row){
                    if($d==config('constants.CASH_FINISH'))
                        return trans('lang.cash_finish');
                    if($d==config('constants.CASH_STOP'))
                        return trans('lang.cash_stop');
                }
            ),
            array('db' => 'user_no', 'dt' => 6,
                'formatter'=>function($d,$row){
                    $point = CashHistory::
                        where('user_no', $d)
                        ->sum('point');
                    $cash_amount = CashHistory::
                    where('user_no', $d)
                        ->sum('cash_amount');

                    return $cash_amount.' / Point '.$point;
                }
            ),
            array('db' => 'no', 'dt' => 7,
                'formatter'=>function($d,$row){
                    $point = CashHistory::sum('cash_amount');
                    return $point;
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