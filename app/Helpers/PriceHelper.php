<?php

namespace App\Helpers;

class PriceHelper
{
    public static function getHikedHotelPrice($price, $user, $hike = 0)
    {
        if (empty($user) || (empty($hike) && empty($user->hotel_hike))) {
            return $price;
        }

        if (empty($hike)) {
            $hike = $user->hotel_hike;
        }

        if (!is_numeric($price)) {
            return $price;
        }

        if (!is_numeric($hike)) {
            return $price;
        }

        return $price + ($price / 100 * $hike);
    }
}

