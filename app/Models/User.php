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

class User extends Model
{
    protected $table = 't_user';

    protected $primaryKey = 'no';
}
