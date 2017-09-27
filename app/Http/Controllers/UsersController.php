<?php

namespace App\Http\Controllers;

use App\Models\AuthCode;
use App\Models\ConsultingReview;
use App\Models\Device;
use App\Models\InAppPurchaseHistory;
use App\Models\ServerFile;
use App\Models\SSP;
use App\Models\User;
use App\Models\UserDeclare;
use App\Models\UserRelation;
use App\Models\Warning;
use App\Models\Withdraw;
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Nexmo;
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

        if (count($params) == 0) {
            $params['rows'] = Config::get('config.itemsPerPage.default');
            $params['page'] = 1;
        }

        return view('users.index', ["params" => $params]);
    }


    /******************************************************************
     *
     * Private Functions
     *
     ******************************************************************/

    private function getUserInfo($user_no)
    {
        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->first();

        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return $response;
        }

        $results->fillInfo();
        return $results;
    }

    /******************************************************************
     *
     * Public Functions
     *
     ******************************************************************/
    public function testFunction()
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
    public function getUserList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $response = User::offset($limit * ($page - 1))->limit($limit)->get();

        return response()->json($response);
    }

    public function getAlarmDisableUserList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if ($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if ($page == null) {
            $params['page'] = 1;
        }

        $response = UserRelation::select('t_user.*')->where('user_no', $user_no)->join('t_user', function ($q) {
                                $q->on('t_user.no', 't_user_relation.relation_user_no');
                                $q->where('t_user_relation.is_alarm',config('constants.FALSE'));
                            })->offset($limit * ($page - 1))->limit($limit)->get();
        for($i = 0; $i < count($response); $i++) {
            $response[$i]->img_file = ServerFile::where('no', $response[$i]->img_no)->first();
        }
        return response()->json($response);
    }

    public function getInitInformation(HttpRequest $request)
    {
        $type = $request->input('os_enum');
        $serial = $request->input('device_id');

        if ($type == null || $serial == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = Device::where('os_enum', $type)->where('device_id', $serial)->first();

        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $results->user_no)->first();
        if ($results == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $user = $results;
        $cur_time = AppServiceProvider::getTimeInDefaultFormat();

        // If it is a exit requested user,
        if ($user->request_exit_flag != config("constants.USER_NORMAL")) {
            $diff_time = AppServiceProvider::diffTime($cur_time, $user->request_exit_time);
            if ($diff_time > 60 * 60 * 24) { // 1 day
                $response = config('constants.ERROR_NO_INFORMATION');
                return response()->json($response);
            } else {
                $response = config('constants.ERROR_REQUESTED_EXIT_USER');
                return response()->json($response);
            }
        }

        // If app stop user or in app stop date
        $diff_time = AppServiceProvider::diffTime($user->app_stop_to_date, $cur_time);
        if($user->app_stop_flag == config('constants.TRUE')) {
            if($diff_time <= 0) {
                $user->app_stop_to_date = null;
                $user->app_stop_from_date = null;
                $user->app_stop_flag = config('constants.FALSE');
                $user->save();
            }
            else {
                $response = config('constants.ERROR_STOPEED_USER');
                return response()->json($response);
            }
        }

        if($user->force_stop_flag == config('constants.TRUE')) {
            $response = config('constants.ERROR_STOPEED_USER');
            return response()->json($response);
        }

        $user->fillInfo();

        // log for login history
        DB::table('t_login_history')->insert(
            [
                'user_no' => $user->no,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        return response()->json($user);
    }

    public function duplicateUser(HttpRequest $request)
    {
        $nickname = $request->input('nickname');

        if ($nickname == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');

        $nickname = $request->input('nickname');
        $results = User::where('nickname', $nickname)->get();

        if ($results != null && count($results) != 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        return response()->json($response);
    }

    public function requestAuthNumber(HttpRequest $request)
    {
        $no = $request->input('no');
        $phone_number = $request->input('phone');

        if ($no == null || $phone_number == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $real_number = $this->getRealPhoneNumber($phone_number);

        $results = User::where('phone_number', $real_number)->get();

        if ($results != null && count($results) > 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        // send Auth Number
        $cert_code = AuthServiceProvider::generateRandomString(6);
        $sms_message = sprintf(config("constants.SMS_TEXT"), $cert_code);
        $phone_number = str_replace("-", "", $phone_number);

        $this->sendSMS("", $phone_number, $sms_message);

        $authcode = new AuthCode();
        $authcode->user_no = $no;
        $authcode->user_phone_number = $real_number;
        $authcode->auth_code = $cert_code;
        $authcode->requested_time = AppServiceProvider::getTimeInDefaultFormat();
        $authcode->save();

        $response['no'] = $authcode->no;
        $response['auth_code'] = $cert_code;
        $response['phone_number'] = $real_number;

        return response()->json($response);
    }

    public function checkAuthNumber(HttpRequest $request)
    {
        $auth_no = $request->input('no');
        $auth_code = $request->input('auth_code');

        if ($auth_no == null || $auth_code == null) {
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
        $testmode = config('constants.testmode');
        if ($testmode != config('constants.TEST_MODE_DELIVERY') || strcmp($authcode->auth_code, $auth_code) == 0) {
            $update_data = [];
            $update_data['verified'] = config("constants.TRUE");
            $update_data['phone_number'] = $authcode->user_phone_number;

            User::where('no', $authcode->user_no)->update($update_data);
        } else {
            $response = config('constants.ERROR_NO_INFORMATION');
        }
        AuthCode::where('no', $auth_no)->delete();

        return response()->json($response);
    }

    public function autoRemoveAuth(HttpRequest $request)
    {
        $auth_no = $request->input('no');

        if ($auth_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        AuthCode::where('no', $auth_no)->delete();

        return response()->json($response);
    }

    public function doUser(HttpRequest $request)
    {
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
        $status = $request->input('status');
        $delete_image = $request->input('del_img');

        $idiomCotroller = new IdiomController();
        if ($oper == 'add') {
            if ($nickname == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $results = User::where('nickname', $nickname)->get();

            if ($results != null && count($results) != 0) {
                $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
                return response()->json($response);
            }

            if ($idiomCotroller->includeForbidden($nickname) == true) {
                $response = config('constants.ERROR_FORBIDDEN_WORD');
                return response()->json($response);
            }

            // image upload
            if ($user_photo != null) {
                $newfile = new ServerFile;
                $user_photo_no = $newfile->uploadFile($user_photo, config('constants.IMAGE'));

                if ($user_photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }
            } else {
                $user_photo_no = -1;
            }

            $user = new User;

            $user->nickname = $nickname;
            $user->sex = $sex;
            $user->age = $age;
            $user->location_no = $location;
            $user->img_no = $user_photo_no;
            $user->subject = $subject;

            if ($latitude != null) {
                $user->latitude = $latitude;
            }

            if ($longitude != null) {
                $user->longitude = $longitude;
            }

            $testmode = config('constants.testmode');
            if ($testmode != config('constants.TEST_MODE_DELIVERY')) {
                $user->point = 30000;
            } else {
                $user->point = config('constants.USER_FIRST_POINT');
            }

            $user->save();
            $user->addPoint(config('constants.POINT_HISTORY_TYPE_SIGN_UP'), 1);
            $response = $this->getUserInfo($user->no);
        } else if ($oper == 'edit') {
            if ($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if ($nickname != null) {
                $results = User::where('nickname', $nickname)->where('no', '!=', $no)->get();

                if ($results != null && count($results) != 0) {
                    $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
                    return response()->json($response);
                }

                if ($idiomCotroller->includeForbidden($nickname) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }

                $update_data['nickname'] = $nickname;
            }
            if ($sex != null) {
                $update_data['sex'] = $sex;
            }
            if ($age != null) {
                $update_data['age'] = $age;
            }
            if ($location != null) {
                $update_data['location_no'] = $location;
            }
            if ($user_photo != null) {
                // image upload
                $newfile = new ServerFile;
                $user_photo_no = $newfile->uploadFile($user_photo, config('constants.IMAGE'));

                if ($user_photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $update_data['img_no'] = $user_photo_no;
            }
            else {
                    if($delete_image == config('constants.TRUE')) {
                        $update_data['img_no'] = -1;
                    }
            }

            if ($subject != null) {
                if ($idiomCotroller->includeForbidden($subject) == true) {
                    $response = config('constants.ERROR_FORBIDDEN_WORD');
                    return response()->json($response);
                }

                $update_data['subject'] = $subject;
            }

            if ($status != null) {
                $update_data['status'] = $status;
            }

            User::where('no', $no)->update($update_data);
            $response = $this->getUserInfo($no);
        } else if ($oper == 'del') {
            if ($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            User::where('no', $no)->delete();
        } else {
            $response = config('constants.ERROR_NO_PARMA');
        }

        return response()->json($response);
    }


    public function doLogin(HttpRequest $request)
    {

        $email = $request->input('f_email');
        $password = $request->input('f_password');
        $facebook_id = $request->input('f_facebook_id');

        $response = config('constants.ERROR_NO');

        if (($password == null || $email == null) && $facebook_id == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('f_email', $email)->get();

        if (!$results || count($results) == 0) {
            $response = config('constants.ERROR_NO_MATCH_INFORMATION');
            return response()->json($response);
        }

        $db_password = $results[0]->f_password;
        if ($db_password != md5($password)) {
            $response = config('constants.ERROR_NO_MATCH_PASSWORD');
            return response()->json($response);
        }

        return response()->json($response);
    }

    public function sendPresent(HttpRequest $request)
    {
        $no = $request->input('from_user_no');
        $friend_no = $request->input('to_user_no');
        $point = $request->input('point');

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user = $results[0];

        $results = User::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $to_user = $results[0];

        // 차단한 유저에게는 전송불가하도록 처리
        $user_relation = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $response = config('constants.ERROR_BLOCK_USER');
            return response()->json($response);
        }

        $user_relation = UserRelation::where('user_no', $friend_no)->where('relation_user_no', $no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $response = config('constants.ERROR_BLOCKED_USER');
            return response()->json($response);
        }

        $ret = $from_user->addPoint(config('constants.POINT_HISTORY_TYPE_PRESENT'), (-1)*$point);
        if($ret == false) {
            $response = config('constants.ERROR_NOT_ENOUGH_POINT');
            return response()->json($response);
        }

        $ret = $to_user->addPoint(config('constants.POINT_HISTORY_TYPE_PRESENT'), $point);

        $data = [];
        $data['point'] = $ret;
        $ret = $this->sendAlarmMessage($from_user->no, $friend_no, config('constants.NOTI_TYPE_SEND_PRESENT'), $data);
        return response()->json($ret);
    }

    public function requestPresent(HttpRequest $request)
    {
        $no = $request->input('from_user_no');
        $friend_no = $request->input('to_user_no');
        $point = $request->input('point');

        if ($no == null || $friend_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $results = User::where('no', $no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user = $results[0];

        $results = User::where('no', $friend_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }

        // 차단한 유저에게는 전송불가하도록 처리
        $user_relation = UserRelation::where('user_no', $no)->where('relation_user_no', $friend_no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $response = config('constants.ERROR_BLOCK_USER');
            return response()->json($response);
        }

        $user_relation = UserRelation::where('user_no', $friend_no)->where('relation_user_no', $no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $response = config('constants.ERROR_BLOCKED_USER');
            return response()->json($response);
        }

        $data = [];
        $data['point'] = $point;
        $ret = $this->sendAlarmMessage($from_user->no, $friend_no, config('constants.NOTI_TYPE_REQUEST_PRESENT'), $data);
        return response()->json($ret);
    }

    public function declareUser(HttpRequest $request)
    {
        $from_user = $request->input('from_user_no');
        $to_user = $request->input('to_user_no');
        $content = $request->input('content');
        $reason = $request->input('reason');

        if ($from_user == null || $to_user == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $results = User::where('no', $to_user)->get();
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

    public function updateLocation(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->get();
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

    public function setAlarmFlag(HttpRequest $request)
    {
        $no = $request->input('no');
        $flag = $request->input('flag');
        $value = $request->input('enable');

        if ($no == null || $flag == null || $value == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $user = $results[0];

        if ($flag == 1) { // call_request
            $user->enable_alarm_call_request = $value;
        } else if ($flag == 2) { // add_friend
            $user->enable_alarm_add_friend = $value;
        }

        $user->save();

        return response()->json($response);
    }

    public function checkRoll(HttpRequest $request)
    {
        $user_no = $request->input('no');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $user = $results[0];
        $response['no'] = config('constants.INVALID_MODEL_NO');
        $curdatetime = AppServiceProvider::getTimeInDefaultFormat(); //current datetime

        $is_ok = false;
        if ($user->check_roll_time != null) {
            $dbdatetime = $user->check_roll_time; //datetime from database: "2014-05-18 18:10:18"

            // 1인안에 1번 check했으면 pass하기.
            $diff = abs(strtotime($curdatetime) - strtotime($dbdatetime));
            if ($diff / 60 > (24 * 60)) {
                $is_ok = true;
            }
        }
        else {
            $is_ok = true;
        }

        if($is_ok == true) {
            $user->addPoint(config('constants.POINT_HISTORY_TYPE_ROLL_CHECK'), 1);
            $response['no'] = config('constants.POINT_ADD_RULE')[config('constants.POINT_HISTORY_TYPE_ROLL_CHECK')];
            $user->check_roll_time = $curdatetime;
            $user->save();
        }

        return response()->json($response);
    }

    public function logForVoiceChat(HttpRequest $request)
    {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $time_in_second = $request->input('time');

        if ($from_user_no == null || $to_user_no == null || $time_in_second == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $from_user = $results[0];

        $results = User::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $to_user = $results[0];
        $min = $time_in_second / 60;

        $type =  config('constants.POINT_HISTORY_TYPE_CHAT');

        $ret = $from_user->addPoint($type, (-1)*$min);
        if ($ret == false) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $ret = $to_user->addPoint($type,  $min);
        $response['no'] = round($ret);

        // add notification
        $data = [];
        $data['time'] = $time_in_second;
        $data['point'] = $ret;
        $this->sendAlarmMessage($from_user->no, $to_user->no, config('constants.NOTI_TYPE_COMPLETE_CONSULTING'), $data);

        return response()->json($response);
    }

    public function writeReviewConsulting(HttpRequest $request)
    {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $mark = $request->input('mark');

        if ($from_user_no == null || $to_user_no == null || $mark == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if ($from_user_no == $to_user_no) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $from_user = $results[0];

        $results = User::where('no', $to_user_no)->get();
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

    public function isAlreadyReviewed(HttpRequest $request) {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        if ($from_user_no == null || $to_user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $review = ConsultingReview::where('from_user_no', $from_user_no)->where('to_user_no',$to_user_no)->first();
        if ($review == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $response = config('constants.ERROR_NO');
        return response()->json($response);
    }

    public function buyPoint(HttpRequest $request)
    {
        $from_user_no = $request->input('user_no');
        $purchase_data = $request->input('purchase_data');//AppServiceProvider::url_decord($request->input('purchase_data'));
        $data_signature = $request->input('data_signature');//AppServiceProvider::url_decord($request->input('data_signature'));

        if ($from_user_no == null || $purchase_data == null || $data_signature == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user_no)->get();

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
        $w_openSslFriendlyKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($w_base64EncodedPublicKeyFromGoogle, 64, "\n") . "-----END PUBLIC KEY-----";
        $w_publicKeyId = openssl_get_publickey($w_openSslFriendlyKey);
        $w_verifyResult = openssl_verify($purchase_data, base64_decode($data_signature), $w_publicKeyId);

        if ($w_verifyResult != 1) {
            $response = config('constants.ERROR_NO_INFORMATION');
            Log::debug('=========================In app Verified Failed=========================');
            Log::debug($purchase_data);
            Log::debug($data_signature);
            Log::debug('=========================In app Verified Failed=========================');
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
            Log::debug('=========================In app Verified Failed=========================');
            Log::debug($w_order_id);
            Log::debug('=========================In app Verified Failed=========================');
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
        for ($i = 0; $i < count($arr_items); $i++) {
            $item = $arr_items[$i];
            if ($item['name'] == $w_purchase_item) {
                $w_purchase_point = $item['value'];
                $w_purchase_price = $item['price'];
            }
        }

        if ($w_purchase_point == 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            Log::debug('=========================In app Verified Failed=========================');
            Log::debug($w_order_id);
            Log::debug('=========================In app Verified Failed=========================');
            return response()->json($response);
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

    public function requestExitUser(HttpRequest $request)
    {
        $user_no = $request->input('user_no');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        $user = $results[0];
        $user->request_exit_flag = config('constants.TRUE');
        $user->request_exit_time = AppServiceProvider::getTimeInDefaultFormat();
        $user->save();

        return response()->json($response);
    }

    public function registerDevice(HttpRequest $request)
    {
        $user_no = $request->input('user_no');
        $device_id = $request->input('device_id');
        $os_enum = $request->input('os_enum');
        $model = $request->input('model');
        $operator = $request->input('operator');
        $api_level = $request->input('api_level');
        $push_service_enum = $request->input('push_service_enum');
        $push_service_token = $request->input('push_service_token');

        if ($user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $user_no)->get();

        if ($results == null || count($results) == 0) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $device = Device::where('os_enum', $os_enum)->where('device_id', $device_id)->first();

        if ($device == null) {
            $device = new Device();
        }

        $device->user_no = $user_no;
        $device->device_id = $device_id;
        $device->os_enum = $os_enum;
        $device->model = $model;
        $device->operator = $operator;
        $device->api_level = $api_level;
        $device->push_service_enum = $push_service_enum;
        $device->push_service_token = $push_service_token;
        $device->save();

        return response()->json($response);
    }

    public function sendAlarm(HttpRequest $request)
    {
        $from_user_no = $request->input('from_user_no');
        $to_user_no = $request->input('to_user_no');
        $type = $request->input('type');
        $data = $request->input('data');

        if ($from_user_no == null || $to_user_no == null || $type == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        if($type != config('constants.NOTI_TYPE_REQUEST_CONSULTING')
            && $type != config('constants.NOTI_TYPE_REQUEST_ACCEPT_CONSULTING')
            && $type != config('constants.NOTI_TYPE_REQUEST_CONSULTING_REFUSE')) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $from_user = User::where('no', $from_user_no)->first();
        if ($from_user == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        $to_user = User::where('no', $to_user_no)->first();
        if ($to_user == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }


        // 상담신청할때
        if($type == config('constants.NOTI_TYPE_REQUEST_CONSULTING')) {
            
            //상담회원 즉 인증된 회원이 아닐때
            if($to_user->verified == config('constants.UNVERIFIED')) {
                $response = config('constants.ERROR_NOT_VERIFIED_USER');
                return response()->json($response);
            }

            // 상담중일때
            if($to_user->status == config('constants.USER_STATUS_CONSULTING')) {
                $response['no'] = $to_user->status;
                $response = config('constants.ERROR_NOW_CONSULTING_USER');
                return response()->json($response);
            }

            // 알림을 끄고 있을때
            if ($to_user->enable_alarm_call_request == config('constants.DISABLE')) {
                $response = config('constants.ERROR_CALL_BLOCK_USER');
                return response()->json($response);
            }

            // 알림차단했을때
            $my_relation = UserRelation::where('user_no', $from_user->no)->where('relation_user_no', $to_user->no)->first();
            if ($my_relation != null && $my_relation->is_alarm == config('constants.DISABLE')) {
                $response = config('constants.ERROR_BLOCK_USER');
                return response()->json($response);
            }

            // 알림차단당했을때
            $other_relation = UserRelation::where('user_no', $from_user->no)->where('relation_user_no', $to_user->no)->first();
            if ($other_relation != null && $other_relation->is_alarm == config('constants.DISABLE')) {
                $response = config('constants.ERROR_BLOCKED_USER');
                return response()->json($response);
            }
        }

        $ret = $this->sendAlarmMessage($from_user_no, $to_user_no, $type, json_decode($data));

        return response()->json($ret);
    }

    public function ajax_user_table()
    {
        $table = 't_user';
        // Custom Where
        $custom_where = " 1=1 ";

        // Table's primary key
        $primaryKey = 'no';

        $sex = $_POST['sex'];
        $user_no = $_POST['user_no'];
        $nickname = $_POST['nickname'];
        $phone_number = $_POST['phone_number'];

        if ($sex != "")
            $custom_where .= " and sex=$sex";
        if ($user_no != "")
            $custom_where .= " and no like '%" . $user_no . "%'";
        if ($nickname != "")
            $custom_where .= " and nickname like '%" . $nickname . "%'";
        if ($phone_number != "")
            $custom_where .= " and phone_number like '%" . $phone_number . "%'";

        $columns = array(
            array('db' => 'no', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    return '<input type="checkbox" class="user_no" value="' . $d . '">';
                }
            ),
            array('db' => 'no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null) {
                        $verified = $results['verified'];
                        if ($verified == '1')
                            return sprintf("%'.05d", $d) . '&nbsp;&nbsp;<span class="badge badge-success">' . trans('lang.talk_insure') . '</span>';
                        else
                            return sprintf("%'.05d", $d);
                    } else
                        return '';
                }
            ),
            array('db' => 'img_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    $results = ServerFile::where('no', $d)->first();
                    if ($results != null)
                        return $results['path'];
                    else
                        return '';
                }
            ),
            array('db' => 'nickname', 'dt' => 3),
            array('db' => 'created_at', 'dt' => 4),
            array('db' => 'no', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    $results = Warning::where('user_no', $d)->get();
                    return count($results);
                }
            ),
            array('db' => 'no', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null) {
                        if ($user_model->force_stop_flag == '1') {
                            return '<span class="badge badge-success">' . trans('lang.force_stop') . '</span>';
                        }
                    } else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null) {
                        if ($user_model->app_stop_flag == '1') {
                            return $user_model->app_stop_from_date . '~' . $user_model->app_stop_to_date;
                        }
                    } else
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

    public function del_selected_profile()
    {
        $selected_user_str = $_POST['selected_user_str'];
        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {

            $user_model = DB::table('t_user')->where('no', $new_selected_array[$i])->first();
            $img_no = $user_model->img_no;
            DB::table('t_file')->where('no', $img_no)->delete();

            $result = DB::update('update t_user set img_no = ?, updated_at = ? where no = ?', [-1, date('Y-m-d H:i:s'), $new_selected_array[$i]]);
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    private function sendWarningAlarm($warning_no, $admin_no)
    {
        $warning = Warning::where('no', $warning_no)->first();

        if ($warning == null) {
            return;
        }
        $data = array();
        $cnt = $this->get_declare_cnt( $warning->user_no);
        $data['cnt'] = $cnt;
        $data['content'] = $warning->admin_memo;
        $this->sendAlarmMessage($admin_no, $warning->user_no, config('constants.NOTI_TYPE_ADMIN_WARING'), $data);
    }

    public function selected_user_warning()
    {
        $selected_user_str = $_POST['selected_user_str'];
        $warning_reason = $_POST['warning_reason'];
        $admin_memo = $_POST['admin_memo'];

        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {

            $no = DB::table('t_warning')->max('no');
            if ($no == null)
                $no = 0;
            $result = DB::table('t_warning')->insert(
                [
                    'no' => ($no + 1),
                    'user_no' => $selected_user_array[$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'warning_reason' => $warning_reason,
                    'admin_memo' => $admin_memo,
                ]
            );

            $this->sendWarningAlarm($no + 1, Session::get('u_no'));

            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function user_force_stop()
    {
        $selected_user_str = $_POST['selected_user_str'];
        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {

            $user = User::where('no', $new_selected_array[$i])->first();

            $update_data['force_stop_flag'] = config('constants.TRUE');
            $query="UPDATE t_user SET force_stop_flag = IF(force_stop_flag=1,NULL,1) WHERE `no`='".$new_selected_array[$i]."'";
            $result = DB::update($query);
            if (!$result)
                return config('constants.FAIL');

            $data = array();
            $data['date'] =  AppServiceProvider::getTimeInDefaultFormat();
            if($user->force_stop_flag == config('constants.TRUE')) {
                $noti_type = config('constants.NOTI_TYPE_ADMIN_APP_STOP_REMOVE');
            }
            else {
                $noti_type = config('constants.NOTI_TYPE_ADMIN_APP_STOP');
            }

            $this->sendAlarmMessage(Session::get('u_no'), $user->no, $noti_type , $data);
        }

        return config('constants.SUCCESS');
    }



    public function stop_app_use()
    {
        $selected_user_str = $_POST['selected_user_str'];
        $stop_days = $_POST['stop_days'];

        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {
            $update_data['app_stop_flag'] = config('constants.TRUE');
            $update_data['app_stop_from_date'] = date('Y-m-d H:i:s');
            $update_data['app_stop_to_date'] = $this->getChangeDate($update_data['app_stop_from_date'], $stop_days);
            $result = User::where('no', $new_selected_array[$i])->update($update_data);
            if (!$result)
                return config('constants.FAIL');

            $user = User::where('no', $new_selected_array[$i])->first();
            $diff_time = AppServiceProvider::diffTime($user->app_stop_to_date, $user->app_stop_to_date);
            $data = array();
            $data['date'] = ($diff_time/60 * 60 * 24);
            $noti_type = config('constants.NOTI_TYPE_ADMIN_APP_STOP');
            $this->sendAlarmMessage(Session::get('u_no'), $user->no, $noti_type , $data);
        }

        return config('constants.SUCCESS');
    }

    public static function getChangeDate($date, $count)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);
        $second = substr($date, 17, 2);

        $date = date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day + $count, $year));
        return $date;
    }

    public function ajax_point_rank_table()
    {
        $table = 't_user';
        // Custom Where
        $custom_where = "1=1";

        // Table's primary key
        $primaryKey = 'no';

        $sex = $_POST['sex'];
        $start_dt = $_POST['start_dt'];
        $end_dt = $_POST['end_dt'];

        if ($sex != "-1")
            $custom_where .= " and sex=$sex";
        if ($start_dt != "")
            $custom_where .= " and created_at>='" . $start_dt . "'";
        if ($end_dt != "")
            $custom_where .= " and created_at<'" . $this->getChangeDate($end_dt, 1) . "'";

        $columns = array(
            array('db' => 'point', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    return $this->get_point_rank($d);
                }
            ),
            array('db' => 'no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    return sprintf("%'.05d", $d);
                }
            ),
            array('db' => 'img_no', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    $results = ServerFile::where('no', $d)->first();
                    if ($results != null)
                        return $results['path'];
                    else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 3,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null) {
                        $html = '<span class="primary-link">' . $user_model->nickname . '(' . $user_model->age . ')' . '</span><br><span>&nbsp;' . $user_model->point . 'P</span>';
                        return $html;
                    } else
                        return '';
                }
            ),
            array('db' => 'no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    $user_model = DB::table('t_user')->where('no', $d)->first();
                    if ($user_model != null)
                        $reg_time = $user_model->created_at;
                    else
                        $reg_time = '';
                    $last_login_time = DB::table('t_login_history')->where('user_no', $d)->max('created_at');
                    return $reg_time . "/" . $last_login_time;
                }
            ),
            array('db' => 'no', 'dt' => 5,
                'formatter' => function ($d, $row) {
                    return $this->get_declare_cnt($d);
                }
            ),
            array('db' => 'no', 'dt' => 6,
                'formatter' => function ($d, $row) {
                    return $this->get_earn_point($d);
                }
            ),
            array('db' => 'no', 'dt' => 7,
                'formatter' => function ($d, $row) {
                    return $this->get_withdraw_point($d);
                }
            ),
            array('db' => 'no', 'dt' => 8,
                'formatter' => function ($d, $row) {
                    return $this->get_receive_present_point($d);
                }
            ),
            array('db' => 'no', 'dt' => 9,
                'formatter' => function ($d, $row) {
                    return $this->get_send_present_point($d);
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

    public function get_declare_cnt($user_no)
    {
        $cnt = DB::table('t_warning')
            ->where('user_no', $user_no)
            ->count('user_no');
        return $cnt;
    }

    public function get_earn_point($user_no)
    {
        $point = DB::table('t_pointhistory')
            ->where('point', '>', 0)
            ->where('user_no', $user_no)
            ->sum('point');
        return $point;
    }

    public function get_withdraw_point($user_no)
    {
        $point = DB::table('t_pointhistory')
            ->where('point', '<', 0)
            ->where('user_no', $user_no)
            ->sum('point');
        return abs($point);
    }

    public function get_send_present_point($user_no)
    {
        $point = DB::table('t_pointhistory')
            ->where('type', 2)
            ->where('user_no', $user_no)
            ->sum('point');
        return abs($point);
    }

    public function get_receive_present_point($user_no)
    {
        $point = DB::table('t_pointhistory')
            ->where('type', 3)
            ->where('user_no', $user_no)
            ->sum('point');
        return $point;
    }

    public function get_point_rank($point)
    {
        $point_list = DB::table('t_user')->select('point')
            ->orderBy('point', 'desc')
            ->groupBy('point')
            ->get();

        for ($i = 1; $i <= count($point_list); $i++) {
            if ($point_list[$i - 1]->point == $point)
                return $i;
        }

        return 1;
    }

    public function ajax_er_user_table()
    {
        $table = 't_user';
        // Custom Where
        $custom_where = "request_exit_flag=" . config('constants.USER_REQUEST_EXIT');

        // Table's primary key
        $primaryKey = 'no';

        $sex = $_POST['sex'];
        $nickname = $_POST['nickname'];
        $phone_number = $_POST['phone_number'];

        if ($sex != "")
            $custom_where .= " and sex=$sex";
        if ($nickname != "")
            $custom_where .= " and nickname like '%" . $nickname . "%'";
        if ($phone_number != "")
            $custom_where .= " and phone_number like '%" . $phone_number . "%'";

        $columns = array(

            array('db' => 'no', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null) {
                        $nickname = $results['nickname'];
                        $verified = $results['verified'];
                        if ($verified == '1')
                            return $nickname . '(' . $d . ')' . '&nbsp;&nbsp;<span class="badge badge-success">' . trans('lang.talk_insure') . '</span>';
                        else
                            return $nickname . '(' . $d . ')';
                    } else
                        return '';
                }
            ),
            array('db' => 'img_no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    $results = ServerFile::where('no', $d)->first();
                    if ($results != null)
                        return $results['path'];
                    else
                        return '';
                }
            ),
            array('db' => 'point', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    return 'P' . number_format($d);
                }
            ),
            array('db' => 'request_exit_time', 'dt' => 3),
            array('db' => 'no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    return '<input type="checkbox" class="user_no" value="' . $d . '">';
                }
            ),
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

    public function selected_user_exit()
    {
        $selected_user_str = $_POST['selected_user_str'];

        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {
            $update_data['request_exit_flag'] = config('constants.USER_EXIT');
            $update_data['exit_time'] = date('Y-m-d H:i:s');
            $result = User::where('no', $new_selected_array[$i])->update($update_data);
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }

    public function ajax_exit_user_table()
    {
        $table = 't_user';
        // Custom Where
        $custom_where = "request_exit_flag=" . config('constants.USER_EXIT');

        // Table's primary key
        $primaryKey = 'no';

        $sex = $_POST['sex'];
        $nickname = $_POST['nickname'];
        $phone_number = $_POST['phone_number'];

        if ($sex != "")
            $custom_where .= " and sex=$sex";
        if ($nickname != "")
            $custom_where .= " and nickname like '%" . $nickname . "%'";
        if ($phone_number != "")
            $custom_where .= " and phone_number like '%" . $phone_number . "%'";
        
        $columns = array(

            array('db' => 'no', 'dt' => 0,
                'formatter' => function ($d, $row) {
                    $results = User::where('no', $d)->first();
                    if ($results != null) {
                        $nickname = $results['nickname'];
                        $verified = $results['verified'];
                        if ($verified == '1')
                            return $nickname . '(' . $d . ')' . '&nbsp;&nbsp;<span class="badge badge-success">' . trans('lang.talk_insure') . '</span>';
                        else
                            return $nickname . '(' . $d . ')';
                    } else
                        return '';
                }
            ),
            array('db' => 'img_no', 'dt' => 1,
                'formatter' => function ($d, $row) {
                    $results = ServerFile::where('no', $d)->first();
                    if ($results != null)
                        return $results['path'];
                    else
                        return '';
                }
            ),
            array('db' => 'point', 'dt' => 2,
                'formatter' => function ($d, $row) {
                    return 'P' . number_format($d);
                }
            ),
            array('db' => 'request_exit_time', 'dt' => 3),
            array('db' => 'no', 'dt' => 4,
                'formatter' => function ($d, $row) {
                    return '<input type="checkbox" class="user_no" value="' . $d . '">';
                }
            ),
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

    public function selected_user_recover()
    {
        $selected_user_str = $_POST['selected_user_str'];

        $selected_user_array = explode(',', $selected_user_str);

        $new_selected_array = array();

        foreach ($selected_user_array as $item) {
            if (!in_array($item, $new_selected_array))
                array_push($new_selected_array, $item);
        }

        for ($i = 0; $i < count($new_selected_array); $i++) {
            $update_data['request_exit_flag'] = config('constants.USER_NORMAL');
            $update_data['exit_time'] = date('Y-m-d H:i:s');
            $result = User::where('no', $new_selected_array[$i])->update($update_data);
            if (!$result)
                return config('constants.FAIL');
        }

        return config('constants.SUCCESS');
    }
}
