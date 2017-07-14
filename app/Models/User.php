<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 't_user';

    protected $primaryKey = 'no';

    public function fillInfo()
    {
        $this->img_file = ServerFile::where('no', $this->img_no)->first();
    }

    public function addPoint($type, $min_point = 0)
    {
        $pointRule = config('constants.POINT_ADD_RULE');
        $real_point = $pointRule[$type];

        if ($type == config('constants.POINT_HISTORY_TYPE_CHAT')) {
            $real_point = $min_point;
        } else if ($type == config('constants.POINT_HISTORY_TYPE_SEND_ENVELOPE') || $type == config('constants.POINT_HISTORY_TYPE_SIGN_UP')
            || $type == config('constants.POINT_HISTORY_TYPE_ROLL_CHECK')
        ) {
            $real_point = $real_point;
        } else {
            $real_point = $min_point;
        }

        $temp_point = $this->point + $real_point;

        if ($temp_point < 0) {
            return false;
        }

        $this->point += $real_point;
        $this->save();

        $point_history = new PointHistory;

        $point_history->type = $type;
        $point_history->point = $real_point;
        $point_history->user_no = $this->no;

        $point_history->save();

        return true;
    }

    public function devices()
    {
        // return $this->hasMany('App\Models\Device');

        $devices = Device::where('user_no', $this->no)->get();
        return $devices;
    }
}
