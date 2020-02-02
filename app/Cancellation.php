<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'booking_code', 'user_id', 'data', 'status'
    ];

    public function getDataAttribute($value) {
        $data = json_decode($value);
        if (empty($data)) {
            return new \stdClass;
        }
        return $data;
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }
}
