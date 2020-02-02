<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'data', 'tour_data','payment_type','is_paid','total_amount', 'transfer_data', 'bookings_raw_xml_data',
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

    public function getTourDataAttribute($value) {
        $data = json_decode($value);
        if (empty($data)) {
            return new \stdClass;
        }
        return $data;
    }

    public function getTransferDataAttribute($value) {
        $data = json_decode($value);
        if (empty($data)) {
            return new \stdClass;
        }
        return $data;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
