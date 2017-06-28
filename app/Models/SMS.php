<?php
/**
 * Created by PhpStorm.
 * User: Hrs
 * Date: 6/27/2017
 * Time: 10:26 AM
 */

namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    protected $table = 't_sms';

    protected $primaryKey = 'no';
}
