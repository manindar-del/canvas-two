<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConvertCurrency extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'base_currency', 'target_currency', 'base_currency_value','target_currency_value',
    ];
}
