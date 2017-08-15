<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/3/2017
 * Time: 12:23 AM
 */

namespace App\Http\Controllers;

use App\Models\CashHistory;
use App\Models\SSP;
use App\Models\User;
use App\Models\Withdraw;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;


class WithdrawController extends BasicController
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
        return view('withdraw.index', ["menu_index" => 6]);
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
    private function testFunction()
    {

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

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $response = null;
        if ($user_no != null) {
            $response = Withdraw::where('user_no', $user_no);
        }
        if ($response != null) {
            $response = $response->offset($limit * ($page - 1))->limit($limit)->get();
        } else {
            $response = Withdraw::offset($limit * ($page - 1))->limit($limit)->get();
        }

        return response()->json($response);
    }

    public function doWithdraw(HttpRequest $request)
    {

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

        if ($oper == 'add') {
            if ($user_no == null || $bank_name == null || $account_number == null || $account_realname == null) {
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
        } else if ($oper == 'edit') {
            if ($id == null || ($account_realname == null && $account_birth == null)) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if ($account_realname != null) {
                $update_data['account_realname'] = $account_realname;
            }
            if ($account_birth != null) {
                $update_data['account_birth'] = $account_birth;
            }

            $results = Withdraw::where('no', $id)
                ->update($update_data);
        } else if ($oper == 'del') {
            if ($id == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            Withdraw::where('no', $id)->delete();
        }

        return response()->json($response);
    }

    public  function getWithdrawRequestTotalPoint(HttpRequest $request) {
        $user_no = $request->input('user_no');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('no', $user_no)->first();
        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        // get unread cnt
        $request_money = Withdraw::where('status', config('constants.WITHDRAW_WAIT'))->where('user_no', $user_no)->sum('money');
        if($request_money == null || empty($request_money)) {
            $request_money = 0;
        }

        $response = config('constants.ERROR_NO');
        $response['request_money'] = $request_money;

        return response()->json($response);
    }

    public function ajax_cash_table()
    {
        $table = 't_cash_history';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $start_dt = $_POST['start_dt'];
        $end_dt = $_POST['end_dt'];
        $status = $_POST['status'];
        $user_no = $_POST['user_no'];
        $nickname = $_POST['nickname'];
        $order_number = $_POST['order_number'];
        $cash_code = $_POST['cash_code'];

        if ($start_dt != "")
            $custom_where .= " and created_at>='" . $start_dt . "'";
        if ($end_dt != "")
            $custom_where .= " and created_at<='" . $this->getChangeDate($end_dt, 1) . "'";
        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%'";
        if ($nickname != "")
            $custom_where .= " and user_no in (select no from t_user where nickname like '%" . $nickname . "%') ";
        if ($status != "-1")
            $custom_where .= " and status ='" . $status . "'";
        if ($order_number != "")
            $custom_where .= " and order_number like '%" . $order_number . "%'";
        if ($cash_code != "")
            $custom_where .= " and cash_code like '%" . $cash_code . "%'";

        global $sum_cash_amount;
        $total_money = DB::select('SELECT sum(cash_amount) as total from t_cash_history WHERE ' . $custom_where);
        if ($total_money != null)
            $sum_cash_amount = $total_money[0]->total;
        else
            $sum_cash_amount = 0;

        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'order_number', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    $results = CashHistory::where('no', $d)->first();
                    if ($results != null)
                        return $results['cash_amount'] . ' / Point ' . $results['point'];
                    else
                        return '';
                }
            ),
            array('db' => 'created_at', 'dt' => 4),
            array('db' => 'status', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.CASH_FINISH'))
                        return trans('lang.cash_finish');
                    if ($d == config('constants.CASH_STOP'))
                        return trans('lang.cash_stop');
                }
            ),
            array('db' => 'user_no', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    $point = CashHistory::
                    where('user_no', $d)
                        ->sum('point');
                    $cash_amount = CashHistory::
                    where('user_no', $d)
                        ->sum('cash_amount');

                    return $cash_amount . ' / Point ' . $point;
                }
            ),
            array('db' => 'no', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    global $sum_cash_amount;
                    return $sum_cash_amount;
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

    public function ajax_withdraw_table()
    {
        $table = 't_withdraw';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $start_dt = $_POST['start_dt'];
        $end_dt = $_POST['end_dt'];
        $status = $_POST['status'];
        $user_no = $_POST['user_no'];
        $account_name = $_POST['account_name'];
        $bank_name = $_POST['bank_name'];

        if ($start_dt != "")
            $custom_where .= " and created_at>='" . $start_dt . "'";
        if ($end_dt != "")
            $custom_where .= " and created_at<='" . $this->getChangeDate($end_dt, 1) . "'";
        if ($status != "-1")
            $custom_where .= " and status ='" . $status . "'";
        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%'";
        if ($account_name != "")
            $custom_where .= " and account_name like '%" . $account_name . "%'";
        if ($bank_name != "")
            $custom_where .= " and bank_name like '%" . $bank_name . "%'";


        global $sum_money;
        $total_money = DB::select('SELECT sum(money) as total from t_withdraw WHERE ' . $custom_where);
        if ($total_money != null)
            $sum_money = $total_money[0]->total;
        else
            $sum_money = 0;

        $columns = array(
            array('db' => 'no', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    return '<input type="checkbox" class="withdraw_no" value="' . $d . '">';
                }
            ),
            array('db' => 'no', 'dt' => 1),
            array('db' => 'user_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    return sprintf("%'.05d", $d);
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
            array('db' => 'money', 'dt' => 4),
            array('db' => 'wait_money', 'dt' => 5),
            array('db' => 'account_name', 'dt' => 6),
            array('db' => 'account_birth', 'dt' => 7),
            array('db' => 'status', 'dt' => 8,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.WITHDRAW_WAIT'))
                        return trans('lang.wait');
                    if ($d == config('constants.WITHDRAW_FINISH'))
                        return trans('lang.finish');
                    if ($d == config('constants.WITHDRAW_ERROR'))
                        return trans('lang.error');
                }
            ),
            array('db' => 'is_verified', 'dt' => 9,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.IS_VERIFIED'))
                        return trans('lang.is_verified');
                    if ($d == config('constants.NOT_VERIFIED'))
                        return trans('lang.not_verified');
                }
            ),
            array('db' => 'created_at', 'dt' => 10),
            array('db' => 'no', 'dt' => 11,
                'formatter' => function ($d, $row) {
                    global $sum_money;
                    return $sum_money;
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

    public function ajax_gifticon_table()
    {
        $table = 't_gifticon';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $start_dt = $_POST['start_dt'];
        $end_dt = $_POST['end_dt'];
        $status = $_POST['status'];
        $user_no = $_POST['user_no'];
        $nickname = $_POST['nickname'];
        $mgr_number = $_POST['mgr_number'];
        $cupon_number = $_POST['cupon_number'];

        if ($start_dt != "")
            $custom_where .= " and created_at>='" . $start_dt . "'";
        if ($end_dt != "")
            $custom_where .= " and created_at<='" . $this->getChangeDate($end_dt, 1) . "'";
        if ($status != "-1")
            $custom_where .= " and status ='" . $status . "'";
        if ($user_no != "")
            $custom_where .= " and user_no like '%" . $user_no . "%'";
        if ($nickname != "")
            $custom_where .= " and user_no in (select no from t_user where nickname like '%" . $nickname . "%') ";
        if ($mgr_number != "")
            $custom_where .= " and mgr_number like '%" . $mgr_number . "%'";
        if ($cupon_number != "")
            $custom_where .= " and cupon_number like '%" . $cupon_number . "%'";


        global $sum_nomal_price;
        $total_money = DB::select('SELECT sum(nomal_price) as total from t_gifticon WHERE ' . $custom_where);
        if ($total_money != null)
            $sum_nomal_price = $total_money[0]->total;
        else
            $sum_nomal_price = 0;

        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'cupon_number', 'dt' => 1),
            array('db' => 'pdt_nm', 'dt' => 2),
            array('db' => 'user_no', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null)
                        return $results['nickname'];
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    $results = DB::table('t_gifticon')->where('no', $d)->first();
                    if ($results != null)
                        return $results->nomal_price . '/' . $results->sale_price;
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    $results = DB::table('t_gifticon')->where('no', $d)->first();
                    if ($results != null)
                        return $results->real_price . '/' . $results->benefit;
                    else
                        return '';
                }
            ),
            array('db' => 'status', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    if ($d == config('constants.GIFTICON_NOMAL'))
                        return trans('lang.nomal');
                    if ($d == config('constants.GIFTICON_CANCEL'))
                        return trans('lang.cancel');
                }
            ),
            array('db' => 'created_at', 'dt' => 7),
            array('db' => 'no', 'dt' => 8,
                'formatter' => function ($d, $row) {
                    global $sum_nomal_price;
                    return $sum_nomal_price;
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

    public function ajax_present_table()
    {
        $table = 'v_point_present_history';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $user_sex = $_POST['sex'];
        $user_no = $_POST['user_no'];
        $user_nickname = $_POST['nickname'];
        $user_phone_number = $_POST['phone_number'];
        $user_email = $_POST['email'];
        $user_chat_content = $_POST['chat_content'];

        if ($user_no != "")
            $custom_where .= " and from_user_no like '%" . $user_no . "%' ";
        if ($user_sex != "-1")
            $custom_where .= " and from_user_no in (select no from t_user where sex='" . $user_sex . "') ";
        if ($user_nickname != "")
            $custom_where .= " and from_user_no in (select no from t_user where nickname like '%" . $user_nickname . "%') ";
        if ($user_phone_number != "")
            $custom_where .= " and from_user_no in (select no from t_user where phone_number like '%" . $user_phone_number . "%') ";
        if ($user_email != "")
            $custom_where .= " and from_user_no in (select no from t_user where email like '%" . $user_email . "%') ";
        if ($user_chat_content != "")
            $custom_where .= " and from_user_no in (select from_user_no from t_notification where content like '%" . $user_chat_content . "%') ";

        $columns = array(
            array('db' => 'no', 'dt' => 0),
            array('db' => 'from_user_no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    $user_model = User::where('no', $d)->first();
                    if ($user_model != null) {
                        $img_model = DB::table('t_file')->where('no', $user_model->img_no)->first();
                        if ($img_model != null)
                            $img_path = $img_model->path;
                        else
                            $img_path = '';

                        if ($user_model->sex == config('constants.MALE'))
                            $sex = '[M]';
                        else
                            $sex = '[F]';

                        $html =
                            '<div class="">
                                <div class="item">
                                    <div class="item-head">
                                        <div class="item-details">';
                        if ($img_path != '')
                            $html .= '<img class="img-circle" src="' . $img_path . '" height="40" width="40">';

                        $html .= '<a class="item-name primary-link">' . $user_model->nickname . ' ' . $sex . '</a>
                                            <span class="item-label">&nbsp; ' . sprintf("%'.05d", $user_model->no) . '</span>
                                        </div>									
                                    </div>	
                                </div>
                            </div>';
                        return $html;
                    } else
                        return '';
                }
            ),
            array('db' => 'from_point', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    return abs($d);
                }
            ),
            array('db' => 'from_date', 'dt' => 3),
            array('db' => 'to_user_no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    $user_model = User::where('no', $d)->first();
                    if ($user_model != null) {
                        $img_model = DB::table('t_file')->where('no', $user_model->img_no)->first();
                        if ($img_model != null)
                            $img_path = $img_model->path;
                        else
                            $img_path = '';

                        if ($user_model->sex == config('constants.MALE'))
                            $sex = '[M]';
                        else
                            $sex = '[F]';

                        $html =
                            '<div class="">
                                <div class="item">
                                    <div class="item-head">
                                        <div class="item-details">';
                        if ($img_path != '')
                            $html .= '<img class="img-circle" src="' . $img_path . '" height="40" width="40">';

                        $html .= '<a class="item-name primary-link">' . $user_model->nickname . ' ' . $sex . '</a>
                                            <span class="item-label">&nbsp; ' . sprintf("%'.05d", $user_model->no) . '</span>
                                        </div>									
                                    </div>	
                                </div>
                            </div>';
                        return $html;
                    } else
                        return '';
                }
            ),
            array('db' => 'to_point', 'dt' => 5),
            array('db' => 'to_date', 'dt' => 6)
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

    public function selected_withdraw_change_status()
    {
        $update_data['status'] = $_POST['status'];
        $selected_withdraw_str = $_POST['selected_withdraw_str'];
        $selected_withdraw_array = explode(',', $selected_withdraw_str);

        for ($i = 0; $i < count($selected_withdraw_array); $i++) {

            $result = Withdraw::where('no', $selected_withdraw_array[$i])->update($update_data);

            if (!$result)
                return config('constants.FAIL');

            // user point minus
            if ( $update_data['status'] == config('constants.WITHDRAW_FINISH')) {
                $withdraw = Withdraw::where('no', $selected_withdraw_array[$i])->first();
                $user_no = $withdraw->user_no;
                $user = User::where('no', $user_no)->first();
                $user->addPoint(config('constants.POINT_HISTORY_TYPE_WITDDRAW'), (-1) * $withdraw->money);
            }
        }

        return config('constants.SUCCESS');
    }
}