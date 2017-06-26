<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotice extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_admin_notice';

    protected $primaryKey = 'no';
}