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

class Warning extends Model
{
    protected $table = 't_warning';

    protected $primaryKey = 'no';
}
