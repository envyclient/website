<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int plan_id
 * @property string charge_id
 * @property string status
 * @property string code
 */
class Coinbase extends Model
{
    protected $table = 'coinbase';

    protected $fillable = [
        'user_id',
        'plan_id',
        'charge_id',
        'status',
        'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
