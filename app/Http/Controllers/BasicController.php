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
use App\Models\UserRelation;
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

    public function sendAlarmMessage($from_user_no, $to_user_no, $message) {
        $type = $message['type'];

        $results = AppUser::where('no', $to_user_no)->get();
        if ($results == null || count($results) == 0) {
            return false;
        }

        $to_user = $results[0];

        if($to_user->enable_alarm == 0) {
            return false;
        }

        $user_relation = UserRelation::where('user_no', $to_user_no)->where('relation_user_no', $from_user_no)->first();

        if($user_relation != null) {
            if ($user_relation->is_alarm == 0) {
                return false;
            }

            if ($type == config('constants.CHATMESSAGE_TYPE_REQUEST_CONSULTING') && $to_user->enable_alarm_call_request == 0) {
                return false;
            }

            if ($type == config('constants.CHATMESSAGE_TYPE_ADD_FRIEND') && $to_user->enable_alarm_add_friend == 0) {
                return false;
            }
        }

        $this->sendXmppMessage($to_user_no, json_encode($message));

        $remove_point = true;
        if($type == config('constants.CHATMESSAGE_TYPE_SEND_PRESENT')) {
            $remove_point = false;
        }

        $this->addNotification($type, $from_user_no, $to_user_no, $message['title'], $message['content'], $remove_point);
        return true;
    }

    private function sendXmppMessage($toUser, $content) {
        $testmode = Config::get('config.testmode');
        if($testmode == 0) {
            $ip = Config::get('config.chatLocalServerIp');
        }
        else {
            $ip = Config::get('config.chatServerIp');
        }
        $tag = Config::get('config.chatAppPrefix');
        $port = Config::get('config.chatServerPort');
        $jidId = $tag.$toUser;

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
        $client->send($message);;
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
           $ret =  $from_user_obj->addPoint(config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE'));
            if($ret == false) {
                $response = config('constants.ERROR_NOT_ENOUGH_POINT');
            }
        }

        $response['no'] = $notification->no;
        return $response;
    }

    public function addImageData($results, $image_no) {
        $file = ServerFile::where('no', $image_no)->first();

        if($file != null) {
            $results->img_checked = $file->checked;
            $results->img_url = $file->path;
        }
        else {
            $results->img_checked = 0;
            $results->img_url = "";
        }
    }
}
