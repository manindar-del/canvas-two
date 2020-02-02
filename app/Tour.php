<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
use App\ConvertCurrency;
use Auth;

class Tour extends Authenticatable
{

    public function getGalleryImageAttribute($value)
    {
        $json = json_decode($value);
        if (empty($json)) {
            return [];
        }
        return $json;
    }

    public function getCancellationDateAttribute($value)
    {
        $json = json_decode($value);
        if (empty($json)) {
            return [];
        }
        return $json;
    }


    // use Notifiable;

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'user_name', 'email', 'password', 'api_token', 'type'
    // ];

    // /**
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
    //  */
    // protected $hidden = [
    //     'password', 'remember_token', 'api_token',
    // ];

    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array
    //  */
    // protected $appends = ['total_spent', 'total_wallet_balance', 'available_wallet_balance', 'completed_bookings_count', 'refund_balance'];

    // public function bookings()
    // {
    //     return $this->hasMany('App\Booking');
    // }

    // public function wallets()
    // {
    //     return $this->hasMany('App\Wallet');
    // }

    // public function cancellations()
    // {
    //     return $this->hasMany('App\Cancellation');
    // }

    // /**
    //  * Get the administrator flag for the user.
    //  *
    //  * @return bool
    //  */
    // public function getTotalSpentAttribute()
    // {
    //     $bookings = $this->bookings()->get();
    //     $total = 0;
    //     foreach ($bookings as $_booking) {
    //         foreach ($_booking->data as $_data) {
    //             if (
    //                 !empty($_data->BookingDetails) &&
    //                 !empty($_data->BookingDetails->BookingId) &&
    //                 !empty($_data->BookingDetails->BookingPrice)
    //             ) {
    //                 $total += ceil($_data->BookingDetails->BookingPrice);
    //             }
    //         }
    //     }
    //     return $total;
    // }

    // public function getTotalWalletBalanceAttribute()
    // {
    //     return $this->wallets()->sum('amount');
    // }

    // public function getAvailableWalletBalanceAttribute()
    // {
    //     return ($this->getTotalWalletBalanceAttribute() + $this->getRefundBalanceAttribute()) + - $this->getTotalSpentAttribute();
    // }

    // public function getRefundBalanceAttribute()
    // {
    //     $cancellations = $this->cancellations()->where('status', 'true')->get();
    //     $total = 0;
    //     foreach ($cancellations as $_cancellation) {
    //         $total += ceil($_cancellation->charges);
    //     }
    //     return $total;
    // }

    // public function getCompletedBookingsCountAttribute()
    // {
    //     $bookings = $this->bookings()->get();
    //     // $cancellations = $this->cancellations()->get();
    //     $count = 0;
    //     $bookings->map(function ($_booking, $key) use ($count) {
    //         foreach ($_booking->data as $_data) {
    //             if (!empty($_data->BookingDetails) && !empty($_data->BookingDetails->BookingId)) {
    //                 // if (!$cancellations->where('booking_id', $_data->BookingDetails->BookingId)->count()) {
    //                 //     $count++;
    //                 // }
    //                 $count++;
    //             }
    //         }
    //     });
    //     return $count;
    // }

    public function getAdultPriceAttribute()
    {
        $user = Auth::user();
        $price = $this->attributes['adult_price'];
        $currency = Session::get('currency');
        // $currency = 'USD';

        if (empty($currency)) {

            return $price;
        }

        $base_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', 'INR')
            ->first();

        $target_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', $currency)
            ->first();

        if (empty($target_currency) || empty($target_currency->target_currency_value)) {
            return $price;
        }


        $target_currency_value = ($price / $base_currency->target_currency_value) * $target_currency->target_currency_value;

        return round($target_currency_value, 2);

    }

    public function getChildPriceAttribute()
    {
        $user = Auth::user();
        $price = $this->attributes['child_price'];

        $currency = Session::get('currency');

        // $currency = 'USD';

        if (empty($currency)) {
            return $price;
        }

        $base_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', 'INR')
            ->first();

        $target_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', $currency)
            ->first();

        if (empty($target_currency) || empty($target_currency->target_currency_value)) {
            return $price;
        }

        $target_currency_value = ($price / $base_currency->target_currency_value) * $target_currency->target_currency_value;


        return  round($target_currency_value, 2);



    }

    public function getInfantPriceAttribute()
    {
        $user = Auth::user();
        $price = $this->attributes['infant_price'];

        $currency = Session::get('currency');
        // $currency = 'USD';

        if (empty($currency)) {
            return  $price;
        }

        $base_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', 'INR')
            ->first();

        $target_currency = ConvertCurrency::where('base_currency', 'EUR')
            ->where('target_currency', $currency)
            ->first();

        if (empty($target_currency) || empty($target_currency->target_currency_value)) {
            return $price;
        }

        $target_currency_value = ($price / $base_currency->target_currency_value) * $target_currency->target_currency_value;

        return   round($target_currency_value, 2);


     }

}
