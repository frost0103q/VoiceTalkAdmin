<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashHistory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_cash_history';

    protected $primaryKey = 'no';
}