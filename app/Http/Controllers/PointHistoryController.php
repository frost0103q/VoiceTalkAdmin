<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/3/2017
 * Time: 12:23 AM
 */

namespace App\Http\Controllers;


use App\Http\Controllers\BasicController;
use App\Models\PointHistory;
use App\Models\AppUser;
use App\Models\ServerFile;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;
use Redirect;
use Request;
use URL;
use Session;
use Socialite;
use Config;


class PointHistoryController  extends BasicController
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
        $params = Request::all();

        if(count($params) == 0) {
            $params['rows'] = Config::get('config.itemsPerPage.default');
            $params['page'] = 1;
        }

        return view('notifications.index', ["params"=>$params]);
    }


    /**
     * getUserList
     *
     * @return json user arrya
     */
    public function pointHistoryList(HttpRequest $request)
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
            $response = PointHistory::where('user_no', $user_no);
        }
        if($response != null) {
            $response = $response->offset($limit * ($page - 1))->limit($limit)->get();
        }
        else {
            $response = PointHistory::offset($limit * ($page - 1))->limit($limit)->get();
        }

        return response()->json($response);
    }

    public function doPointHistory(HttpRequest $request){

        $oper = $request->input("oper");
        $user_no = $request->input('user_no');
        $type = $request->input('type');
        $point = $request->input('point');

        $no = $request->input("no");

        $response = config('constants.ERROR_NO');

        if($oper == 'add') {
            if($user_no == null || $type == null || $point == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $withdraw = new PointHistory;
            $withdraw->user_no = $user_no;
            $withdraw->type = $type;
            $withdraw->point = $point;

            $withdraw->save();
            $response['no'] = $withdraw->no;
        }
        else if($oper == 'edit') {
            if($no == null || ($type == null && $user_no == null)) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if($type != null) {
                $update_data['type'] = $type;
            }
            if($user_no != null) {
                $update_data['user_no'] = $user_no;
            }

            $results = PointHistory::where('no', $no)
                ->update($update_data);
        }
        else if($oper == 'del') {
            if($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            PointHistory::where('no', $no)->delete();
        }

        return response()->json($response);
    }
}