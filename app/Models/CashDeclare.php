<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashDeclare extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_cash_declare';

    protected $primaryKey = 'no';
}