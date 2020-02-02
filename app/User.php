<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'password', 'api_token', 'type',
        'company_name', 'company_address', 'country', 'city', 'phone', 'fax',
        'website', 'salutation', 'position',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_spent', 'total_wallet_balance', 'available_wallet_balance', 'completed_bookings_count', 'refund_balance',
    ];

    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }

    public function wallets()
    {
        return $this->hasMany('App\Wallet');
    }

    public function cancellations()
    {
        return $this->hasMany('App\Cancellation');
    }

    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getTotalSpentAttribute()
    {
        $bookings = $this->bookings()->where('is_paid', '1')->get();
        $bookings = $this->bookings()->get();
        $total = 0;

        // calculate hotel booking cost
        foreach ($bookings as $_booking) {
            foreach ($_booking->data as $_data) {
                if (
                    !empty($_data->BookingDetails) &&
                    !empty($_data->BookingDetails->BookingId) &&
                    !empty($_data->BookingDetails->BookingPrice)
                ) {
                    $total += ceil($_data->BookingDetails->BookingPrice);
                }
            }
        }

        // calculate tour booking cost
        foreach ($bookings as $_booking) {
            if (empty($_booking->tour_data)) {
                continue;
            }
            foreach ($_booking->tour_data as $_booking_data) {
                $adult = $_booking_data->form->form->adult;
                $child = $_booking_data->form->form->child;
                $infant = $_booking_data->form->form->infant;
                $adult_price = $_booking_data->tour->adult_price;
                $child_price = $_booking_data->tour->child_price;
                $infant_price = $_booking_data->tour->infant_price;
                $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
                $total += $total_tour_rate;
            }
        }

        // calculate transfer booking cost
        foreach ($bookings as $_booking) {
            if (empty($_booking->transfer_data)) {
                continue;
            }
            foreach ($_booking->transfer_data as $_booking_data) {
                $adult = $_booking_data->form->form->adult;
                $child = $_booking_data->form->form->child;
                $adult_price = $_booking_data->transfer->adult_price;
                $child_price = $_booking_data->transfer->child_price;
                $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
                $total += $total_transfer_rate;
            }
        }

        return $total;
    }

    public function getTotalWalletBalanceAttribute()
    {
        return $this->wallets()->sum('amount');
    }

    public function getRemainingBalance()
    {
        die;
        $balance = ($this->getTotalWalletBalanceAttribute() + $this->getRefundBalanceAttribute()) - $this->getTotalSpentAttribute();
        return floor($balance);
    }

    public function getAvailableWalletBalanceAttribute()
    {
        die;
        $balance = ($this->getTotalWalletBalanceAttribute() + $this->getRefundBalanceAttribute()) - $this->getTotalSpentAttribute();
        return floor($balance);
    }

    public function getRefundBalanceAttribute()
    {
        $bookings = $this->bookings()->where('is_paid', '1')->get();
        $cancellations = $this->cancellations()->where('status', 'true')->get();
        $total = 0;

        foreach ($bookings as $_booking) {

            // find total hotel cancellation charges
            if(!empty($_booking->data)){
                if (empty($_booking->data)) {
                    continue;
                }
                foreach ($_booking->data as $_bookingdata) {
                    if (empty($_bookingdata->BookingDetails->BookingId)) {
                        continue;
                    }
                    $BookingId = $_bookingdata->BookingDetails->BookingId;
                    foreach ($cancellations as $_cancellation) {
                        $cancellation_id = $_cancellation->booking_id;
                        if($cancellation_id ==$BookingId ){
                            $total += $_bookingdata->BookingDetails->BookingPrice;
                        }
                    }
                }
            }

            // find total tour cancellation charges
            if(!empty($_booking->tour_data)) {
                foreach ($_booking->tour_data as $_bookingdata) {
                    $BookingId = $_bookingdata->BookingId;
                    foreach ($cancellations as $_cancellation) {
                        $cancellation_id = $_cancellation->booking_id;
                        if ($cancellation_id == $BookingId) {
                            $adult = $_bookingdata->form->form->adult;
                            $child = $_bookingdata->form->form->child;
                            $infant = $_bookingdata->form->form->infant;
                            $adult_price = $_bookingdata->tour->adult_price;
                            $child_price = $_bookingdata->tour->child_price;
                            $infant_price = $_bookingdata->tour->infant_price;
                            $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
                            $total += $total_tour_rate - $_cancellation->charges;
                        }
                    }
                }
            }

            // find total transfer cancellation charges
            if(!empty($_booking->transfer_data)) {
                foreach ($_booking->transfer_data as $_bookingdata) {
                    $BookingId = $_bookingdata->BookingId;
                    foreach ($cancellations as $_cancellation) {
                        $cancellation_id = $_cancellation->booking_id;
                        if ($cancellation_id == $BookingId) {
                            $adult = $_bookingdata->form->form->adult;
                            $child = $_bookingdata->form->form->child;
                            $adult_price = $_bookingdata->transfer->adult_price;
                            $child_price = $_bookingdata->transfer->child_price;
                            $total_tour_rate = ($adult * $adult_price) + ($child * $child_price);
                            $total += $total_tour_rate - $_cancellation->charges;
                        }
                    }
                }
            }

        }

        return floor($total);
    }

    /**
     * Get total cancellation charges
     *
     * @return float
     */
    public function getCanellationCharges()
    {
        $bookings = $this->bookings()->where('is_paid', '1')->get();
        $cancellations = $this->cancellations()->where('status', 'true')->get();
        $total = 0;

        foreach ($bookings as $_booking) {

            // find total tour cancellation charges
            if(!empty($_booking->tour_data)) {
                foreach ($_booking->tour_data as $_bookingdata) {
                    $BookingId = $_bookingdata->BookingId;
                    foreach ($cancellations as $_cancellation) {
                        $cancellation_id = $_cancellation->booking_id;
                        if ($cancellation_id == $BookingId) {
                            $total += $_bookingdata->BookingDetails->BookingPrice;
                        }
                    }
                }
            }

            // find total transfer cancellation charges
            if(!empty($_booking->transfer_data)) {
                foreach ($_booking->transfer_data as $_bookingdata) {
                    $BookingId = $_bookingdata->BookingId;
                    foreach ($cancellations as $_cancellation) {
                        $cancellation_id = $_cancellation->booking_id;
                        if ($cancellation_id == $BookingId) {
                            $total += $_bookingdata->BookingDetails->BookingPrice;
                        }
                    }
                }
            }

        }

        return $total;
    }

    public function getCompletedBookingsCountAttribute()
    {
        $bookings = $this->bookings()->get();
        // $cancellations = $this->cancellations()->get();
        $count = 0;
        $bookings->map(function ($_booking, $key) use ($count) {
            foreach ($_booking->data as $_data) {
                if (!empty($_data->BookingDetails) && !empty($_data->BookingDetails->BookingId)) {
                    // if (!$cancellations->where('booking_id', $_data->BookingDetails->BookingId)->count()) {
                    //     $count++;
                    // }
                    $count++;
                }
            }
        });
        return $count;
    }

}
