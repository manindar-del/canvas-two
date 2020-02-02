<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

}
