<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserRelation;
use App\Providers\AppServiceProvider;
use App\Services\FCMHandler;
use Config;
use DB;
use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Protocol\Message;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Message\OptionsBuilder;
use Nexmo;
use Redirect;
use Request;
use Session;
use SMS;
use Socialite;
use URL;

class BasicController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendAlarmMessage($from_user_no, $to_user_no, $type, $data = null)
    {
        $from_user = User::where('no', $from_user_no)->first();
        if ($from_user == null) {
            return config('constants.ERROR_NO_INFORMATION');
        }

        $message = Notification::getInstance($type, $from_user, $data);

        $results = User::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }

        $to_user = $results[0];

        $can_send_push = true;
        if ($type == config('constants.NOTI_TYPE_ADD_FRIEND') && $to_user->enable_alarm_add_friend == config('constants.DISABLE')) {
            $can_send_push = false;
        }

        $user_relation = UserRelation::where('user_no', $to_user_no)->where('relation_user_no', $from_user_no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $can_send_push = false;
        }

        $user_relation = UserRelation::where('user_no', $from_user_no)->where('relation_user_no', $to_user_no)->first();
        if ($user_relation != null && $user_relation->is_alarm == config('constants.DISABLE')) {
            $can_send_push = false;
        }

        $push_mode = config('constants.pushmode');

        if($can_send_push == true) {
            if ($push_mode == config('constants.PUSH_MODE_FCM')) {
                $title = config('app.name');
                if ($message->title != null) {
                    $title = $message->title;
                }

                $content = $message;
                if ($message->content != null) {
                    $content = $message->content;
                }

                $this->sendFCMMessage($to_user, $title, $content, $message->toArray());
            } else if ($push_mode == config('constants.PUSH_MODE_XMPP')) {
                $this->sendXmppMessage($to_user_no, json_encode($message));
            }
        }

        $this->addNotification($type, $from_user_no, $to_user_no, $message->title, $message->content, $data);
        return config('constants.ERROR_NO');
    }

    private function sendXmppMessage($toUser, $content)
    {
        $testmode = config('constants.testmode');
        if ($testmode == config('constants.TEST_MODE_LOCAL')) {
            $ip = Config::get('config.chatLocalServerIp');
        } else {
            $ip = Config::get('config.chatServerIp');
        }
        $tag = Config::get('config.chatAppPrefix');
        $port = Config::get('config.chatServerPort');
        $jidId = $tag . $toUser;

        $options = new Options("tcp://" . $ip . ":" . $port);
        $options->setAuthenticated(false)
            ->setUsername($jidId)
            ->setPassword($jidId)
            ->setTimeout(300)
            ->setPeerVerification(false);

        $client = new Client($options);

        // optional connect manually
        $client->connect();

        // send a message to another user
        $message = new Message;
        $message->setMessage($content)
            ->setTo($jidId . '@' . $ip);
        $client->send($message);;
    }

    public function getXmppHistory(HttpRequest $request)
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

        $response = config('constants.ERROR_NO');


        $ip = Config::get('config.chatServerIp');
        $tag = Config::get('config.chatAppPrefix');
        $jidId = $tag . $user_no;
        $port = Config::get('config.chatServerPort');

        $options = new Options("tcp://" . $ip . ":" . $port);
        $options->setAuthenticated(false)
            ->setUsername($jidId)
            ->setPassword($jidId)
            ->setTimeout(300)
            ->setPeerVerification(false);

        $client = new Client($options);

        // optional connect manually
        $client->connect();

        $client->sendWithIQ("<iq type='get' id='" . $jidId . "'>
                                <list xmlns='urn:xmpp:archive'
                                    with='" . $jidId . "@" . $ip . "'>
                                        <set xmlns='http://jabber.org/protocol/rsm'>
                                            <max>30</max>
                                        </set>
                                    </list>
                            </iq>");


        // how to get call back.
        return response()->json($response);
    }

    private function sendFCMMessage($user, $title, $str_message, $body)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $to = $user->devices()->pluck('push_service_token')->toArray();

        if (!empty($to)) {
            $fcm = new FCMHandler();

            // FCMHandler 덕분에 코드는 이렇게 한 줄로 간결해졌다.
            // notification($title, $str_message) body에 포함되여 있음.
            $response = $fcm->to($to)->data($body)->send();
            if ($response->numberFailure() > 0) {
                return false;
            }
        }

        return true;
    }

    public function addNotification($type, $from_user, $to_user, $title, $content, $data)
    {
        if ($from_user == null || $to_user == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = User::where('no', $from_user)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        };

        $results = User::where('no', $to_user)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }

        $notification = new Notification();
        $notification->type = $type;
        $notification->title = $title;
        $notification->content = $content;
        $notification->from_user_no = $from_user;
        $notification->to_user_no = $to_user;
        $milliseconds = AppServiceProvider::getMilliTime();
        $notification->created_militime = $milliseconds;

        if ($data == null) {
            $notification->data = "";
        } else {
            $notification->data = json_encode($data);
        }

        $notification->save();

        $response['no'] = $notification->no;
        return $response;
    }

    public function  getRealPhoneNumber($phone_number)
    {
        $testmode = config('constants.testmode');

        if ($testmode == config('constants.TEST_MODE_LOCAL')) {
            $real_number = '+86' . $phone_number;
        } else {
            $real_number = '+82' . $phone_number;
        }
        return $real_number;
    }

    public function  sendSMS($sender, $phone_number, $message, $save = true)
    {
        $real_number = $this->getRealPhoneNumber($phone_number);

        if($sender == null || empty($sender) == true) {
             $sender = Config::get('config.defaultSender');
        }

        /*************************************************************************
            SMS::send($message, null, function ($sms) use ($real_number) {
                $sms->from('+8615699581631');
                $sms->to($real_number);
            }
         *************************************************************************/
        /*************************************************************************
            Nexmo::message()->send([
                'to' => $phone_number,
                'from' => '01028684884',
                'text' => 'Using the facade to send a message.'
            ]);
         *************************************************************************/
        Log::debug( "sendSMS ==>"."$sender : $phone_number");
        $this->sendSMSByMunjaNara($sender, $phone_number, $message);

        if ($save == true) {
            $sms = new \App\Models\SMS();
            $sms->sender_number = $sender;
            $sms->receive_number = $real_number;
            $sms->content = $message;
            $sms->save();
        }
    }

    public function sendSMSByMunjaNara($sender, $hpReceiver, $hpMesg)
    {
        $userid = "apptom1313";          // 문자나라 아이디 wooju0716
        $passwd = "kakavotalk";          // 문자나라 비밀번호 tmdwn0927
        $hpSender = $sender;            // 보내는분 핸드폰번호 02-1004-1004

        /*  UTF-8 글자셋 이용으로 한글이 깨지는 경우에만 주석을 푸세요. */
        $hpMesg = iconv("UTF-8", "EUC-KR", "$hpMesg");
        /*  ---------------------------------------- */
        $hpMesg = urlencode($hpMesg);
        $endAlert = 0;  // 전송완료알림창 ( 1:띄움, 0:안띄움 )

        $url = "/MSG/send/web_admin_send.htm?userid=$userid&passwd=$passwd&sender=$hpSender&receiver=$hpReceiver&encode=1&end_alert=$endAlert&message=$hpMesg";
        Log::debug( "send SMS Parameter ==>"."$url");
        $fp = fsockopen("211.233.20.184", 80, $errno, $errstr, 10);
        if (!$fp) {
            echo "$errno : $errstr";
            Log::debug( "sendSMS ==>"."$errno : $errstr");
        }

        fwrite($fp, "GET $url HTTP/1.0\r\nHost: 211.233.20.184\r\n\r\n");
        $flag = 0;
        $out = "";
        while (!feof($fp)) {
            $row = fgets($fp, 1024);

            if ($flag) $out .= $row;
            if ($row == "\r\n") $flag = 1;
        }
        fclose($fp);
        Log::debug( "sendSMS  Result ==>"."$out");
        return $out;
    }

    public function ajax_upload()
    {

        if (!empty($_FILES["uploadfile"])) {
            $filename = $_FILES['uploadfile']['name'];
            $file_extension = pathinfo($_FILES['uploadfile']['name'], PATHINFO_EXTENSION);

            $uploaddir = dirname(__DIR__) . "/../../public/uploads/";
            $filename = time(). $filename;
            $file = $uploaddir . "/" .$filename;

            if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
                if (file_exists($file)) {
                    die(json_encode(array('filename' => $filename, 'fileurl' => asset('uploads/' . $filename), 'result' => config('constants.SUCCESS'))));
                }
            } else {
                return config('constants.FAIL');
            }
        }

        return config('constants.SUCCESS');
    }

    public function file_download()
    {
        $file_name = $_GET['file_name'];

        $uploaddir = dirname(__DIR__) . "/../../public/uploads/";
        $file = $uploaddir . "/" . $file_name;

        $fp = fopen($file, "r");
        $cont = fread($fp, filesize($file));
        fclose($fp);

        header('Pragma: ');
        header('Cache-Control: cache');
        header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
        header("Content-Type: application/octet-stream; filename=\"" . $file_name . "\"");

        echo $cont;
    }

    public static function getChangeDate($date, $count)
    {
        $year = intval(substr($date, 0, 4));
        $month = intval(substr($date, 5, 2));
        $day = intval(substr($date, 8));
        $date = date("Y-m-d", mktime(0, 0, 0, $month, $day + $count, $year));
        return $date;
    }

    public static function getDayCount($start, $end)
    {
        $s_year = intval(substr($start, 0, 4));
        $s_month = intval(substr($start, 5, 2));
        $s_day = intval(substr($start, 8));
        $e_year = intval(substr($end, 0, 4));
        $e_month = intval(substr($end, 5, 2));
        $e_day = intval(substr($end, 8));

        $s_time = mktime(0, 0, 0, $s_month, $s_day, $s_year);
        $e_time = mktime(0, 0, 0, $e_month, $e_day, $e_year);

        $days = ($e_time - $s_time) / 24 / 60 / 60;
        return abs($days);
    }
}
