<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Protocol\Roster;
use Fabiang\Xmpp\Protocol\Presence;
use Fabiang\Xmpp\Protocol\Message;

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

class BasicController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendAlarmMessage($toUser, $content) {
        $ip = Config::get('config.chatServerIp');
        $tag = Config::get('config.chatAppPrefix');
        $jidId = $tag.$toUser;
        $port = Config::get('config.chatServerPort');

        $options = new Options("tcp://".$ip.":".$port);
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
            ->setTo($jidId.'@'.$ip);
        $client->send($message);

        return response()->json("Test");
    }

    public function getXmppHistory(HttpRequest  $request) {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $user_no = $request->input('user_no');

        if($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if($page == null) {
            $params['page'] = 1;
        }

        $response = config('constants.ERROR_NO');


        $ip = Config::get('config.chatServerIp');
        $tag = Config::get('config.chatAppPrefix');
        $jidId = $tag.$user_no;
        $port = Config::get('config.chatServerPort');

        $options = new Options("tcp://".$ip.":".$port);
        $options->setAuthenticated(false)
            ->setUsername($jidId)
            ->setPassword($jidId)
            ->setTimeout(300)
            ->setPeerVerification(false);

        $client = new Client($options);

        // optional connect manually
        $client->connect();

        $client->sendWithIQ("<iq type='get' id='".$jidId."'>
                                <list xmlns='urn:xmpp:archive'
                                    with='".$jidId."@".$ip."'>
                                        <set xmlns='http://jabber.org/protocol/rsm'>
                                            <max>30</max>
                                        </set>
                                    </list>
                            </iq>");


        // how to get call back.
        return response()->json($response);
    }

    public function addNotification($type, $from_user, $to_user, $title, $content, $need_point = true) {
        if($from_user == null || $to_user == null || $content == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = AppUser::where('no', $from_user)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }
        $from_user_obj = $results[0];
        $results = AppUser::where('no', $to_user)->get();
        if ($results == null || count($results) == 0) {
            return config('constants.ERROR_NO_INFORMATION');
        }


        $results = Notification::where('type', $type)->where('title', $title)->where('content', $content)
            ->where('from_user_no', $from_user)->where('to_user_no', $to_user)->get();

        if ($results == null || count($results) == 0) {
            $notification = new Notification();
            $notification->type = $type;
            $notification->title = $title;
            $notification->content = $content;
            $notification->from_user_no = $from_user;
            $notification->to_user_no = $to_user;
        }
        else {
            $notification = $results[0];
            $notification->unread_count = $notification->unread_count + 1;
        }

        $notification->save();

        if($need_point == true) {
            $from_user_obj->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'));
        }

        $response['no'] = $notification->no;
        return $response;
    }

    public function ajax_upload(){

        if (!empty($_FILES["uploadfile"])) {
            $filename = $_FILES['uploadfile']['name'];
            $file_extension = pathinfo($_FILES['uploadfile']['name'], PATHINFO_EXTENSION);

            $uploaddir =dirname(__DIR__) . "/../../public/uploads/";
            $file = $uploaddir . "/" . $filename;

            if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
                if (file_exists($file)) {
                    die(json_encode(array('filename' => $filename, 'fileurl' => asset('uploads/'.$filename), 'result' => config('constants.SUCCESS'))));
                }
            } else {
                return config('constants.FAIL');
            }
        }
        return config('constants.SUCCESS');
    }

    public function file_download(){
        $file_name=$_GET['file_name'];

        $uploaddir =dirname(__DIR__) . "/../../public/uploads/";
        $file = $uploaddir . "/" . $file_name;

        $fp = fopen( $file, "r" );
        $cont = fread( $fp, filesize($file) );
        fclose( $fp );

        header('Pragma: ');
        header('Cache-Control: cache');
        header ( "Content-Disposition: attachment; filename=\"".$file_name."\"" );
        header ( "Content-Type: application/octet-stream; filename=\"".$file_name."\"" );

        echo $cont;
    }
}
