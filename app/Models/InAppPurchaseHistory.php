<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InAppPurchaseHistory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_inapp_purchase_history';

    protected $primaryKey = 'no';
}