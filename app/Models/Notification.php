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
        if($type == config('constants.NOTI_TYPE_CHATMESSAGE')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_CONSULTING')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_ACCEPT_CONSULTING')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_REQUEST_PRESENT')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname, $data['point']);
        }
        else if($type == config('constants.NOTI_TYPE_SEND_ENVELOP')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_ADD_FRIEND')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname);
        }
        else if($type == config('constants.NOTI_TYPE_SEND_PRESENT')) {
            $title = config('constants.NOTI_TITLE_CONTENT')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TITLE_CONTENT')[$type]['content'], $from_user->nickname, $data['point']);
        }
        else if($type == config('constants.NOTI_TYPE_CASH_QA')) {
            $title = config('constants.NOTI_TYPE_REQUEST_CONSULTING')[$type]['title'];
            $content = sprintf(config('constants.NOTI_TYPE_REQUEST_CONSULTING')[$type]['content'], $from_user->nickname);
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
