<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/18/2017
 * Time: 11:13 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    protected $table = 't_authcode';

    protected $primaryKey = 'no';
}