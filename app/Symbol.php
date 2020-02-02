<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency', 'sign'
    ];
}
