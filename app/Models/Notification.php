<?php

namespace App\Models;

use App\Providers\AppServiceProvider;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 't_notification';

    protected $primaryKey = 'no';

    public static function getInstance($type, $from_user, $data=null)
    {
        $notifcation = new Notification();
        $noti_content = config('constants.NOTI_TITLE_CONTENT');
        $title = $noti_content[$type]['title'];

        if($type == config('constants.NOTI_TYPE_CHATMESSAGE')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_CONSULTING')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_ACCEPT_CONSULTING')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_PRESENT')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname, $data['point']);
        }
        else if($type == config('constants.NOTI_TYPE_SEND_ENVELOP')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_ADD_FRIEND')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_SEND_PRESENT')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname, $data['point']);
        }
        else if($type == config('constants.NOTI_TYPE_CASH_QA')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_ADMIN_NORMAL_PUSH')) {
            $content = sprintf($noti_content[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REFUSE_IMAGE')) {
            $content = $noti_content[$type]['content'];
        }

        $notifcation->type = $type;
        $notifcation->user = $from_user;
        $notifcation->time = AppServiceProvider::getTimeInDefaultFormat();
        $notifcation->content = $content;
        $notifcation->title = $title;
        $notifcation->data = json_encode($data);

        return $notifcation;
    }
}
