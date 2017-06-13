<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 6/13/2017
 * Time: 4:13 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory  extends Model
{
    protected $table = 't_chathistory';

    protected $primaryKey = 'no';
}