<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\AuthCode;
use App\Models\ConsultingReview;
use App\Models\InAppPurchaseHistory;
use App\Models\Notification;
use App\Models\ServerFile;
use App\Models\SSP;
use App\Models\User;
use App\Models\UserDeclare;
use App\Models\Warning;
use App\Providers\AuthServiceProvider;
use App\Providers\AppServiceProvider;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use SMS;
use Socialite;
use URL;

class UsersController extends BasicController
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
		
		return view('users.index', ["params"=>$params]);
	}


    /******************************************************************
     *
     * Private Functions
     *
    ******************************************************************/

    private function getUserInfo($user_no) {
        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $user_no)->first();

        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return $response;
        }

        $this->addImageData($results, $results->img_no);
        $result = array_merge ( $results->toArray(), $response);

        return $result;
    }

    private function sendPoint($from_user, $to_user, $point, $type=0) {
        $no = $from_user;
        $friend_no = $to_user;

        if($no == null || $friend_no == null || $point == null) {
            return config('constants.ERROR_NO_PARMA');
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }

        $from = $results[0];
        if($from->point < $point) {
            return  config('constants.ERROR_NOT_ENOUGH_POINT');
        }

        $results = AppUser::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $to = $results[0];


        $ret = true;
        if($type == config('constants.POINT_HISTORY_TYPE_SEND_PRESENT')) {
            $ret = $from->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_PRESENT'), (-1)*$point);
            if($ret == true) {
                $ret = $to->addPoint(config('constants.POINT_HISTORY_TYPE_RECEIVE_PRESENT'), $point);
            }
        }
        else if($type == config('constants.POINT_HISTORY_TYPE_CHAT')) {
            $ret = $from->addPoint($type, (-1) * $point);
            if($ret == true) {
                $ret = $to->addPoint($type, $point);
            }
        }
        else {
            $ret = $from->addPoint(config('constants.POINT_HISTORY_TYPE_NORMAL'), (-1)*$point);
            if($ret == true) {
                $ret = $to->addPoint(config('constants.POINT_HISTORY_TYPE_NORMAL'), $point);
            }
        }

        if($ret == false) {
            $response = config('constants.ERROR_NOT_ENOUGH_POINT');
        }
        return $response;
    }

    /******************************************************************
     *
     * Public Functions
     *
     ******************************************************************/
    public function testFunction() {

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
	public function getUserList(HttpRequest $request)
	{
		$limit = $request->input('rows');
		$page = $request->input('page');
		
		if($limit == null) {
			$limit = Config::get('config.itemsPerPage.default');
		}
		
		if($page == null) {
			$params['page'] = 1;
		}
		
		$response = AppUser::offset($limit*($page - 1))->limit($limit)->get();
		
		return response()->json($response);
	}


    public function getInitInformation(HttpRequest $request) {
        $type = $request->input('device_type');
        $serial = $request->input('device_serial');

        if($type == null || $serial == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('device_type', $type)->where('device_serial', $serial)->first();

        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $this->addImageData($results, $results->img_no);
        $result = array_merge ( $results->toArray(), $response);

        $response = Notification::select('unread_count')->where('unread_count', '>', 0)->where('to_user_no', $results->no)->sum('unread_count');;
        $result['unread_notification_cnt'] = $response;

        return response()->json($result);
    }



    public function duplicateUser(HttpRequest $request){
        $nickname = $request->input('nickname');

        if($nickname == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');

        $nickname = $request->input('nickname');
        $results = AppUser::where('nickname', $nickname)->get();

        if ($results != null && count($results) != 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        return response()->json($response);
    }

    public function requestAuthNumber(HttpRequest $request) {
        $no = $request->input('no');
        $phone_number = $request->input('phone');

        if($no == null || $phone_number == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = AppUser::where('phone_number', $phone_number)->get();

        if ($results != null && count($results) > 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        // send Auth Number
        $cert_code = AuthServiceProvider::generateRandomString(6);
        $sms_message = "VoiceTalk인증코드는 ".$cert_code." 입니다.";
        $phone_number = str_replace("-","", $phone_number);

        SMS::send($sms_message, null, function($sms) use ($phone_number) {
            $debug = config('app.debug');
            $testmode = Config::get('config.testmode');
            if($testmode == 0) {
                $phone_number = '+86'.$phone_number; //'+8615699581631'
                $sms->to($phone_number);
            }
            else {
                $phone_number = '+82'.$phone_number;
                $sms->to($phone_number);
            }
        });

        $authcode = new AuthCode();
        $authcode->user_no = $no;
        $authcode->user_phone_number = $phone_number;
        $authcode->auth_code = $cert_code;
        $date = date('Y-m-d H:i:s');
        $authcode->requested_time = $date;
        $authcode->save();
        $response['no'] = $authcode->no;
        $response['auth_code'] = $cert_code;

        return response()->json($response);
    }

    public function checkAuthNumber(HttpRequest $request) {
        $auth_no = $request->input('no');
        $auth_code = $request->input('auth_code');

        if($auth_no == null || $auth_code == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AuthCode::where('no', $auth_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $authcode = $results[0];
        if(strcmp($authcode->auth_code, $auth_code) == 0) {
            $update_data = [];
            $update_data['verified'] = 1;
            $update_data['phone_number'] = $authcode->user_phone_number;

            AppUser::where('no', $authcode->user_no)->update($update_data);
        }
        else {
            $response = config('constants.ERROR_NO_INFORMATION');
        }
        AuthCode::where('no', $auth_no)->delete();

        return response()->json($response);
    }

    public function autoRemoveAuth(HttpRequest $request) {
        $auth_no = $request->input('no');

        if($auth_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        AuthCode::where('no', $auth_no)->delete();

        return response()->json($response);
    }
	
	public function doUser(HttpRequest $request){
		$response = config('constants.ERROR_NO');

		$oper = $request->input("oper");
		$no = $request->input('no');
		$nickname = $request->input('nickname');
        $sex = $request->input('sex');
        $location = $request->input('location_no');
        $age = $request->input('age');
        $subject = $request->input('subject');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $user_photo = $request->file('user_photo');
        $device_type = $request->input('device_type');
        $device_serial = $request->input('device_serial');
        $device_token = $request->input('device_token');
        $status = $request->input('status');

		if($oper == 'add') {	
			if($nickname == null || $subject == null) {
				$response = config('constants.ERROR_NO_PARMA');
				return response()->json($response);
			}

            $results = AppUser::where('nickname', $nickname)->get();

            if ($results != null && count($results) != 0) {
                $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
                return response()->json($response);
            }

            // image upload
            if($user_photo != null) {
                $newfile = new ServerFile;
                $user_photo_no = $newfile->uploadFile($user_photo, TYPE_IMAGE);

                if ($user_photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }
            }
            else {
                $user_photo_no = -1;
            }

			$user = new AppUser;
				
			$user->nickname = $nickname;
			$user->sex = $sex;
            $user->age = $age;
			$user->location_no = $location;
            $user->img_no = $user_photo_no;
            $user->subject = $subject;
            $user->device_type = $device_type;
            $user->device_serial = $device_serial;
            $user->device_token = $device_token;

            if($latitude != null) {
                $user->latitude = $latitude;
            }

            if($longitude != null) {
                $user->longitude = $longitude;
            }
			
			$user->save();
            $user->addPoint(config('constants.POINT_HISTORY_TYPE_SIGN_UP'));
            $response = array_merge ( $user->toArray(), $response);
		}
		else if($oper == 'edit') {
            if($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

			$update_data = [];
			if($nickname != null) {
                $results = AppUser::where('nickname', $nickname)->where('no', '!=' , $no)->get();

                if ($results != null && count($results) != 0) {
                    $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
                    return response()->json($response);
                }

				$update_data['nickname'] = $nickname;
			}
            if($sex != null) {
                $update_data['sex'] = $sex;
            }
            if($age != null) {
                $update_data['age'] = $age;
            }
            if($location != null) {
                $update_data['location_no'] = $location;
            }
            if($user_photo != null) {
                // image upload
                $newfile = new ServerFile;
                $user_photo_no = $newfile->uploadFile($user_photo, TYPE_IMAGE);

                if($user_photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $update_data['img_no'] = $user_photo_no;
            }

            if($subject != null) {
                $update_data['subject'] = $subject;
            }
            if($device_type != null) {
                $update_data['device_type'] = $device_type;
            }
            if($device_serial != null) {
                $update_data['device_serial'] = $device_serial;
            }
            if($device_token != null) {
                $update_data['device_token'] = $device_token;
            }

            if($status != null) {
                $update_data['status'] = $status;
            }

			AppUser::where('no', $no)->update($update_data);
            $response = $this->getUserInfo($no);
		}
		else if($oper == 'del') {
			if($no == null) {
				$response = config('constants.ERROR_NO_PARMA');
				return response()->json($response);
			}
			
			AppUser::where('no', $no)->delete();
		}
		else {
			$response = config('constants.ERROR_NO_PARMA');
		}
		
		return response()->json($response);
	}
	
	
	public function doLogin(HttpRequest $request) {
		
		$email = $request->input('f_email');
		$password = $request->input('f_password');
		$facebook_id = $request->input('f_facebook_id');
		
		$response = config('constants.ERROR_NO');
		
		if(($password == null || $email == null) && $facebook_id == null) {
			$response = config('constants.ERROR_NO_PARMA');
			return response()->json($response);
		}
	
		$results = AppUser::where('f_email', $email)->get();
	
		if (!$results || count($results) == 0) {
			$response = config('constants.ERROR_NO_MATCH_INFORMATION');
			return response()->json($response);
		}

		$db_password = $results[0]->f_password;
		if($db_password != md5($password)) {
			$response = config('constants.ERROR_NO_MATCH_PASSWORD');
			return response()->json($response);
		}
	
		return response()->json($response);
	}

    public function sendPresent(HttpRequest $request) {
        $no = $request->input('no');
        $friend_no = $request->input('to_user_no');
        $point = $request->input('point');

        $response = $this->sendPoint($no, $friend_no, $point, config('constants.POINT_HISTORY_TYPE_SEND_PRESENT'));

        if($response['error'] != 0) {
            return response()->json($response);
        }

        $results = AppUser::where('no', $no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user =  $results[0];

        $message = [];
        $message['type'] = config('constants.CHATMESSAGE_TYPE_SEND_PRESENT');
        $message['user_no'] = $no;
        $message['user_name'] = $from_user->nickname;

        $file = ServerFile::where('no', $from_user->img_no)->first();
        if($file != null) {
            $message['user_img_url'] = $file->path;
        }
        else {
            $message['user_img_url'] = "";
        }
        $message['time'] = "";
        $message['content'] = $from_user->nickname."님이 당신에게 ".$point."포인트를 선물했습니다.";
        $message['title'] = "선물";
        $message['talk_no'] = "";
        $message['talk_user_no'] = "";

        $this->sendAlarmMessage($from_user->no, $friend_no, $message);
        return response()->json($response);
    }

    public function declareUser(HttpRequest $request) {
        $from_user = $request->input('from_user_no');
        $to_user = $request->input('to_user_no');
        $content = $request->input('content');
        $reason = $request->input('reason');

        if($from_user == null || $to_user == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = AppUser::where('no', $to_user)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $declare = new UserDeclare;
        $declare->from_user_no = $from_user;
        $declare->to_user_no = $to_user;
        $declare->content = $content;
        $declare->reason = $reason;
        $declare->save();

        return response()->json($response);
    }

    public function updateLocation(HttpRequest $request) {
        $device_serial = $request->input('device_serial');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('device_serial', $device_serial)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $result = $results[0];
        $result->latitude = $latitude;
        $result->longitude = $longitude;
        $result->save();

        return response()->json($response);
    }

    public function setAlarmFlag(HttpRequest $request){
        $no = $request->input('no');
        $flag = $request->input('flag');
        $value = $request->input('enable');

        if($no == null || $flag == null || $value == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $user = $results[0];

        if($flag == 0) {  // alarm
            $user->enable_alarm_call_request = $value;
            $user->enable_alarm_add_friend = $value;
        }
        else if($flag == 1) { // call_request
            $user->enable_alarm_call_request = $value;
        }
        else if($flag == 2) { // add_friend
            $user->enable_alarm_add_friend = $value;
        }

        if($user->enable_alarm_call_request == 1 || $user->enable_alarm_add_friend  == 1) {
            $user->enable_alarm = 1;
        }
        else {
            $user->enable_alarm = 0;
        }

        $user->save();

        return response()->json($response);
    }

    public function sendAlarm(HttpRequest $request){
        $type = $request->input('type');
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $content = $request->input('message');

        if($from_user_no == null || $to_user_no == null || $content == null || $type == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = AppUser::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $to_user = $results[0];
        $message = json_decode($content, true);
        $this->sendAlarmMessage($from_user_no, $to_user->no, $message);
        return response()->json($response);
    }

    public function checkRoll(HttpRequest $request) {
        $user_no = $request->input('no');

        if($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $user = $results[0];
        $response['no'] = 0;
        $curdatetime = date("Y-m-d H:i:s"); //current datetime
        if($user->check_roll_time != null) {
            $dbdatetime = $user->check_roll_time;//datetime from database: "2014-05-18 18:10:18"

            $diff = abs(strtotime($curdatetime) - strtotime($dbdatetime));
            if($diff/60 > (24*60)) {
                $user->addPoint(config('constants.POINT_HISTORY_TYPE_ROLL_CHECK'));
                $response['no'] = config('constants.POINT_ADD_RULE')[config('constants.POINT_HISTORY_TYPE_ROLL_CHECK')];
                $user->check_roll_time =  $curdatetime;
                $user->save();
            }
        }
        else {
            $user->addPoint(config('constants.POINT_HISTORY_TYPE_ROLL_CHECK'));
            $response['no'] = config('constants.POINT_ADD_RULE')[config('constants.POINT_HISTORY_TYPE_ROLL_CHECK')];
            $user->check_roll_time =  $curdatetime;
            $user->save();
        }

        return response()->json($response);
    }

    public function logForVoiceChat(HttpRequest $request) {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $time_in_second = $request->input('time');

        if($from_user_no == null || $to_user_no == null || $time_in_second == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $from_user = $results[0];

        $results = AppUser::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $to_user = $results[0];
        $point = $time_in_second/60 * 200;
        $response = $this->sendPoint($from_user_no, $to_user_no, $point, config('constants.POINT_HISTORY_TYPE_CHAT'));
        $response['no'] = round($point);
        return response()->json($response);
    }

    public function writeReviewConsulting(HttpRequest $request) {
        $from_user_no  = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $mark = $request->input('mark');

        if($from_user_no == null || $to_user_no == null || $mark == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if($from_user_no == $to_user_no) {
            $response = config('constants.ERROR_NOT_ENABLE_SELF_REVIEW');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $from_user = $results[0];

        $results = AppUser::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $to_user = $results[0];

        $consulting_review = new ConsultingReview;

        $consulting_review->from_user_no = $from_user_no;
        $consulting_review->to_user_no = $to_user_no;
        $consulting_review->mark = $mark;

        $consulting_review->save();

        return response()->json($response);
    }

    public function buyPoint(HttpRequest $request) {
        $from_user_no  = $request->input('user_no');
        $purchase_data = AppServiceProvider::url_decord($request->input('purchase_data'));
        $data_signature = AppServiceProvider::url_decord($request->input('data_signature'));

        if($from_user_no == null || $purchase_data == null || $data_signature == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $from_user = $results[0];


        //
        // 구매내역 검증.
        //
        // Decrypt($p_data_signature, 공개키) == SHA1($p_purchase_data)
        //
        $google_key = Config::get('config.google')['google_iab_public_key'];
        $w_base64EncodedPublicKeyFromGoogle = $google_key;
        $w_openSslFriendlyKey = "-----BEGIN PUBLIC KEY-----\n".chunk_split($w_base64EncodedPublicKeyFromGoogle, 64, "\n")."-----END PUBLIC KEY-----";
        $w_publicKeyId = openssl_get_publickey($w_openSslFriendlyKey);
        $w_verifyResult = openssl_verify($purchase_data, base64_decode($data_signature), $w_publicKeyId);

        if ($w_verifyResult != 1) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        //
        // 거래 유일성 체크.
        //
        $w_jsonPurchaseData = json_decode($purchase_data, true);
        $w_order_id = $w_jsonPurchaseData['orderId'];
        $results = InAppPurchaseHistory::where('order_id', $w_order_id)->get();
        if ($results != null && count($results) > 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }


        //
        // 포인트 증가.
        //
        $w_purchase_item = $w_jsonPurchaseData['productId'];
        $w_purchase_point = 0;
        $w_purchase_price = 0;
        //
        // [2014/12/17 17:46]새 아이템 적용.
        //
        $arr_items = config('constants.INAPP_ITEMS');
        $w_purchase_point = 0;
        $w_purchase_price = 0;
        for($i = 0; $i  < count($arr_items); $i++) {
            $item = $arr_items[$i];
            if($item['name'] == $w_purchase_item) {
                $w_purchase_point = $item['value'];
                $w_purchase_price = $item['price'];
            }
        }

        $from_user->addPoint(config('constants.POINT_HISTORY_TYPE_INAPP'), $w_purchase_point);

        //
        // 아이피주소 체크.
        //
        $ip = "unknown";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }


        //
        // 구매내역 보관.
        //
        $inapp_history = new InAppPurchaseHistory();
        $inapp_history->user_no = $from_user->no;
        $inapp_history->order_id = $w_order_id;
        $inapp_history->purchase_data = $purchase_data;
        $inapp_history->data_signature = $data_signature;
        $inapp_history->ip = $ip;
        $inapp_history->price = $w_purchase_price;
        $inapp_history->save();

        $response['current_point'] = $from_user->point;
        $response['purchased_point'] = $w_purchase_point;
        return response()->json($response);
    }



    public function ajax_user_table(){
        $table = 't_user';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sex=$_POST['sex'];
        $user_no=$_POST['user_no'];
        $nickname=$_POST['nickname'];
        $phone_number=$_POST['phone_number'];
        $email=$_POST['email'];
        $chat_content=$_POST['chat_content'];

        if($sex!="")
            $custom_where.=" and sex=$sex";
        if($user_no!="")
            $custom_where.=" and no like '%".$user_no."%'";
        if($nickname!="")
            $custom_where.=" and nickname like '%".$nickname."%'";
        if($phone_number!="")
            $custom_where.=" and phone_number like '%".$phone_number."%'";
        if($email!="")
            $custom_where.=" and email like '%".$email."%'";
        if($chat_content!="")
            $custom_where.=" and no in (select from_user_no from t_chathistory where content like '%".$chat_content."%') ";

        $columns = array(
            array('db' => 'no', 'dt' => 0,
                'formatter'=>function($d,$row){
                    return '<input type="checkbox" class="user_no" value="'.$d.'">';
                }
            ),
            array('db' => 'no', 'dt' => 1,
                'formatter'=>function($d,$row){
                    $results = User::where('no', $d)->first();
                    if($results!=null){
                        $verified=$results['verified'];
                        if($verified=='1')
                            return sprintf("%'.05d", $d).'&nbsp;&nbsp;<span class="badge badge-success">'.trans('lang.talk_insure').'</span>';
                        else
                            return sprintf("%'.05d", $d);
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'img_no', 'dt' => 2,
                'formatter'=>function($d,$row){
                    $results = ServerFile::where('no', $d)->first();
                    if($results!=null)
                        return $results['path'];
                    else
                        return '';
                }
            ),
            array('db' => 'nickname', 'dt' => 3),
            array('db' => 'subject', 'dt' => 4),
            array('db' => 'created_at', 'dt' => 5),
            array('db' => 'no', 'dt' => 6,
                'formatter'=>function($d,$row){
                    $results = Warning::where('user_no', $d)->get();
                    return count($results);
                }
            ),
            array('db' => 'no', 'dt' => 7,
                'formatter'=>function($d,$row){
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if($user_model!=null){
                        if($user_model->force_stop_flag=='1'){
                            return '<span class="badge badge-success">'.trans('lang.force_stop').'</span>';
                        }
                    }
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 8,
                'formatter'=>function($d,$row){
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if($user_model!=null){
                        if($user_model->app_stop_flag=='1'){
                            return $user_model->app_stop_from_date.'~'.$user_model->app_stop_to_date;
                        }
                    }
                    else
                        return '';
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

    public function del_selected_profile(){
        $selected_user_str=$_POST['selected_user_str'];
        $selected_user_array=explode(',',$selected_user_str);

        $new_selected_array=array();

        foreach ($selected_user_array as $item){
            if(!in_array($item,$new_selected_array))
                array_push($new_selected_array,$item);
        }
        
        for($i=0;$i<count($new_selected_array);$i++){

            $result=DB::update('update t_user set img_no = ?, updated_at = ? where no = ?',[-1,date('Y-m-d H:i:s'),$new_selected_array[$i]]);
            if(!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function selected_user_warning(){
        $selected_user_str=$_POST['selected_user_str'];
        $warning_reason=$_POST['warning_reason'];
        $admin_memo=$_POST['admin_memo'];

        $selected_user_array=explode(',',$selected_user_str);

        $new_selected_array=array();

        foreach ($selected_user_array as $item){
            if(!in_array($item,$new_selected_array))
                array_push($new_selected_array,$item);
        }

        for($i=0;$i<count($new_selected_array);$i++){

            $no=DB::table('t_warning')->max('no');
            if($no==null)
                $no=0;
            $result=DB::table('t_warning')->insert(
                [
                    'no'=>($no+1),
                    'user_no' => $selected_user_array[$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'warning_reason' =>$warning_reason,
                    'admin_memo' =>$admin_memo,
                ]
            );

            if(!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function user_force_stop(){
        $selected_user_str=$_POST['selected_user_str'];
        $selected_user_array=explode(',',$selected_user_str);

        $new_selected_array=array();

        foreach ($selected_user_array as $item){
            if(!in_array($item,$new_selected_array))
                array_push($new_selected_array,$item);
        }

        for($i=0;$i<count($new_selected_array);$i++){
            $update_data['force_stop_flag']=1;
            $result=User::where('no', $new_selected_array[$i])->update($update_data);
            if(!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function stop_app_use(){
        $selected_user_str=$_POST['selected_user_str'];
        $stop_days=$_POST['stop_days'];

        $selected_user_array=explode(',',$selected_user_str);

        $new_selected_array=array();

        foreach ($selected_user_array as $item){
            if(!in_array($item,$new_selected_array))
                array_push($new_selected_array,$item);
        }

        for($i=0;$i<count($new_selected_array);$i++){
            $update_data['app_stop_flag']=1;
            $update_data['app_stop_from_date']=date('Y-m-d H:i:s');
            $update_data['app_stop_to_date']=$this->getChangeDate($update_data['app_stop_from_date'],$stop_days);
            $result=User::where('no', $new_selected_array[$i])->update($update_data);
            if(!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public static function getChangeDate($date,$count){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8,2);
        $hour = substr($date, 11,2);
        $minute = substr($date, 14,2);
        $second = substr($date, 17,2);

        $date = date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day + $count, $year));
        return $date;
    }
}
