<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string invoice_id
 * @property string payment_id
 * @property string payer_id
 * @property string token
 */
class Invoice extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
