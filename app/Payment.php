<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'user_id', 'status', 'date', 'request', 'response', 'txn_id'
    ];

    public function getRequestAttribute()
    {
        try {
            return json_decode($this->attributes['request']);
        } catch (\Exception $e) {
            return (object) [];
        }
    }

    public function setRequestAttribute($value)
    {
        $this->attributes['request'] = json_encode($value);
    }

    public function getResponseAttribute()
    {
        try {
            return json_decode($this->attributes['response']);
        } catch (\Exception $e) {
            return (object) [];
        }
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = json_encode($value);
    }
}
