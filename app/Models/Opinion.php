<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_opinion';

    protected $primaryKey = 'no';
}