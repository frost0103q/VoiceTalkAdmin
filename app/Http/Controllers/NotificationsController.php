<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use App\Models\Notification;
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

class NotificationsController extends BasicController
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
		$params = Request::all();
		
		if(count($params) == 0) {
			$params['rows'] = Config::get('config.itemsPerPage.default');
			$params['page'] = 1;
		}		
		
		return view('notifications.index', ["params"=>$params]);
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
	public function notificationList(HttpRequest $request)
	{
		$limit = $request->input('rows');
		$page = $request->input('page');
		
		if($limit == null) {
			$limit = Config::get('config.itemsPerPage.default');
		}
		
		if($page == null) {
			$params['page'] = 1;
		}


        $read = $request->input('read') == null? 0 :  $request->input('read');
        $user_no = $request->input('user_no');
        $type = $request->input('type') == null? 0 :  $request->input('type');

		$response = Notification::select('*');
        if($read != null && $read >= 0) {
            $response = $response->where('unread_count', '>', 0);
        }

        if($type != null && $type >= 0) {
            $response = $response->where('type', $type);
        }

        if($user_no != null) {
            $response = $response->where('to_user_no', $user_no);
        }


        $response = $response->orderBy('updated_at', 'desc')->offset($limit*($page - 1))->limit($limit)->get();


        for($i = 0; $i < count($response); $i++) {
            $notification = $response[$i];
            $user = AppUser::where('no', $notification->from_user_no)->first();
            if($user != null) {
                $imagefile = ServerFile::where('no', $user->img_no)->first();

                if($imagefile != null) {
                    $user->img_url = $imagefile->path;
                }
                else {
                    $user->img_url = "";
                }
            }

            $response[$i]->user = $user;
        }

		return response()->json($response);
	}
	
	public function doNotification(HttpRequest $request){
		
		$oper = $request->input("oper");
		$text = $request->input('title');
		$type = $request->input('type');
		$from_id = $request->input('from_user_no');
		$to_id = $request->input('to_user_no');
		$content = $request->input('content');
		
		$id = $request->input("no");
		
		$response = config('constants.ERROR_NO');
		
		if($oper == 'add') {
            $response = $this->addNotification($type, $from_id, $to_id, $text, $content);
		}
		else if($oper == 'edit') {
			if($id == null ) {
				$response = config('constants.ERROR_NO_PARMA');
				return response()->json($response);
			}
				
			$update_data = [];
			if($content != null) {
				$update_data['content'] = $content;
			}
			if($text != null) {
				$update_data['title'] = $text;
			}
			
			$results = Notification::where('no', $id)->update($update_data);
		}
		else if($oper == 'del') {
			if($id == null) {
				$response = config('constants.ERROR_NO_PARMA');
				return response()->json($response);
			}
			
			Notification::where('no', $id)->delete();
		}
		
		return response()->json($response);
	}


    public function sendEnvelop(HttpRequest $request) {

        $user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $content = $request->input('content');
        $title = config('constants.NOTI_TITLE_SEND_ENVELOPE');
        $type = config('constants.NOTI_TYPE_SEND_ENVELOPE');

        $response = $this->addNotification($type, $user_no, $to_user_no, $title, $content);

        return response()->json($response);
    }

    public function sendGroupEnvelop(HttpRequest $request) {
        $user_no = $request->input('from_user_no');
        $sex = $request->input('sex');
        $order = $request->input('order');
        $content = $request->input('content');
        $cur_lat = $request->input('cur_lat');
        $cur_lng = $request->input('cur_lng');

        $title = config('constants.NOTI_TITLE_SEND_ENVELOPE');
        $type = config('constants.NOTI_TYPE_SEND_ENVELOPE');
        $count = config('constants.NOTI_GROUP_LIMIT');

        if($order == 0 && ($cur_lat == null || $cur_lng == null)) {  // order by distancer
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if($user_no == null || $sex == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = AppUser::where('no', $user_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user =  $results[0];

        $query = DB::table('t_user')->select('t_user.no')->where('sex', $sex)->where('no', '!=' , $user_no);

        if($order == 0) { // distance
            $dist = DB::raw('(ROUND(6371 * ACOS(COS(RADIANS('.$cur_lat.')) * COS(RADIANS(t_user.latitude)) * COS(RADIANS(t_user.longitude) - RADIANS('.$cur_lng.')) + SIN(RADIANS('.$cur_lat.')) * SIN(RADIANS(t_user.latitude))),2))');
            $query = $query->orderBy($dist)->get();
        }
        else {
            $query = $query->orderBy('created_at', 'desc');
        }

        $results = $query->offset(0)->limit($count)->get();
        for($i = 0; $i < count($results); $i++) {
            $to_user_no = $results[$i];
            $message = [];
            $message['type'] = config('constants.CHATMESSAGE_TYPE_SEND_ENVELOP');
            $message['user_no'] = $from_user->no;
            $message['user_name'] = $from_user->nickname;

            $file = ServerFile::where('no', $from_user->img_no)->first();
            if($file != null) {
                $message['user_img_url'] = $file->path;
            }
            else {
                $message['user_img_url'] = "";
            }
            $message['time'] = "";
            $message['content'] = $content;
            $message['title'] = "쪽지전송";
            $message['talk_no'] = "";
            $message['talk_user_no'] = "";

            $this->sendAlarmMessage($to_user_no->no, json_encode($message));
            $this->addNotification($type, $from_user->no, $to_user_no->no, $message['title'], $message['content']);
        }

        $results = AppUser::where('no', $from_user->no)->get();
        $from_user =  $results[0];
        return response()->json($from_user);
    }

    public function setAllEnvelopFlag(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $flag = $request->input('flag');

        if($user_no == null || $flag == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if($flag == 0) {  // read all
            $update_data = [];
            $update_data['unread_count'] = 0;

            Notification::where('to_user_no', $user_no)->update($update_data);
        }
        else if($flag == 1) { // delete all
            Notification::where('to_user_no', $user_no)->delete();
        }

        $response = config('constants.ERROR_NO');
        return response()->json($response);
    }

}