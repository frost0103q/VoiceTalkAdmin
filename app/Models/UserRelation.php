<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/25/2017
 * Time: 1:37 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Config;

class UserRelation extends Model
{
    protected $table = 't_user_relation';

    protected $primaryKey = 'no';

    public function __construct()
    {
        $this->is_alarm = config('constants.TRUE');
        $this->is_friend = config('constants.FALSE');
    }
}
