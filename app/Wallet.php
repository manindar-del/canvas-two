<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount','user_id','payment_id', 'slug'
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
