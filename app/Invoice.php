<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string token
 * @property string payer_id
 * @property string payment_id
 * @property string invoice_id
 * @property int user_id
 */
class Invoice extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
