<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Session;
use App\ConvertCurrency;

class Transfer extends Model
{
    /**
     * Decode Gallery Images
     *
     * @param string $value
     * @return array
     */
    public function getGalleryImageAttribute($value)
    {
        $json = json_decode($value);
        if (empty($json)) {
            return [];
        }
        return $json;
    }

    /**
     * Decode Cancellation Dates
     *
     * @param string $value
     * @return array
     */
    public function getCancellationDateAttribute($value)
    {
        $json = json_decode($value);
        if (empty($json)) {
            return [];
        }
        return $json;
    }

    public function getAdultPriceAttribute()
    {
        $user = Auth::user();
        $price = $this->attributes['adult_price'];

        if (empty($user) || empty($user->transfer_hike)) {
            // return $price;
        }

        $price = $price + ($price / 100 * $user->transfer_hike);

        $currency = Session::get('currency');

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



         return round($target_currency_value, 1);




    }
}
