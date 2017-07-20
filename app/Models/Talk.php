<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/19/2017
 * Time: 1:52 PM
 */

namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    protected $table = 't_talk';

    protected $primaryKey = 'no';

    public static function getInstanceFromStd($var)
    {
        $talk_modal = new Talk();
        $talk_modal->no = $var->no;
        $talk_modal->subject = $var->subject;
        $talk_modal->voice_type = $var->voice_type;
        $talk_modal->created_at = $var->created_at;
        $talk_modal->updated_at = $var->updated_at;
        $talk_modal->user_no = $var->user_no;
        $talk_modal->img_no = $var->img_no;
        $talk_modal->type = $var->type;
        return $talk_modal;
    }

    public function fillInfo()
    {
        $user = User::where('no', $this->user_no)->first();
        $user->fillInfo();
        $this->user = $user;

        $this->voice_file = ServerFile::where('no', $this->voice_no)->first();
        $this->img_file = ServerFile::where('no', $this->img_no)->first();

        $arr_voice_type = config('constants.TALK_VOICE_TYPE');
        $this->voice_type_string = $arr_voice_type[$this->voice_type];
    }
}
