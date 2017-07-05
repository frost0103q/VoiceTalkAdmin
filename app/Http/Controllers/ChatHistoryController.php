<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/13/2017
 * Time: 4:15 PM
 */

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;

class ChatHistoryController extends BasicController
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
        return view('welcome');
    }

    /**
     * getUserList
     *
     * @return json user arrya
     */
    public function chatHistoryList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');

        if($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if($page == null) {
            $params['page'] = 1;
        }

        $response = ChatHistory::select('*');
        if($user_no != null && $to_user_no != null) {
            $response = $response->where(['from_user_no' => $user_no,  'to_user_no' => $to_user_no])->orWhere(['to_user_no' => $user_no,  'from_user_no' => $to_user_no]);
        }

        $response = $response->orderBy('updated_at', 'desc')->offset($limit * ($page - 1))->limit($limit)->get();

        return response()->json($response);
    }

    public function doChatHistory(HttpRequest $request){

        $oper = $request->input("oper");
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $message = $request->input('message');

        $response = config('constants.ERROR_NO');

        if($oper == 'add') {
            if($from_user_no == null || $to_user_no == null || $message == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $history = new ChatHistory;
            $history->from_user_no = $from_user_no;
            $history->to_user_no = $to_user_no;
            $history->content = $message;

            $history->save();
            $response['no'] = $history->no;

            $type = config('constants.CHATMESSAGE_TYPE_NORMAL');

            $this->addNotification($type, $from_user_no, $to_user_no, "일반채팅", $message, false);
        }
        else if($oper == 'edit') {

            $no = $request->input('no');

            if($no == null || ($from_user_no == null && $to_user_no == null && $message == null)) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if($from_user_no != null) {
                $update_data['from_user_no'] = $from_user_no;
            }
            if($to_user_no != null) {
                $update_data['to_user_no'] = $to_user_no;
            }
            if($message != null) {
                $update_data['content'] = $message;
            }

            $results = ChatHistory::where('no', $no)->update($update_data);
        }
        else if($oper == 'del') {
            $no = $request->input('no');
            if($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            ChatHistory::where('no', $no)->delete();
        }

        return response()->json($response);
    }
}