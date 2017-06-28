<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/3/2017
 * Time: 12:23 AM
 */

namespace App\Http\Controllers;


use App\Models\Withdraw;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;


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
}