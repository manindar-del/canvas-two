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
        'user_name', 'name', 'email', 'password', 'api_token', 'company_name',
        'company_address', 'country', 'city', 'phone', 'fax', 'website',
        'salutation', 'position',
        'name', 'user_name', 'email', 'password', 'api_token', 'type'
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
    protected $appends = ['total_spent', 'total_wallet_balance', 'available_wallet_balance', 'completed_bookings_count', 'refund_balance'];
    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }
    public function wallets()
    {
        return $this->hasMany('App\Wallet')->with(['payment']);
    }
    public function cancellations()
    {
        return $this->hasMany('App\Cancellation');
    }
    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getTotalSpentAttribute()
    {
        $bookings = $this->bookings()
            ->where('is_paid', '1')
            ->where('payment_type', 'wallet')
            ->get();
        // $bookings = $this->bookings()->get();
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
    //     // calculate tour booking cost
    //     foreach ($bookings as $_booking) {
    //         if (empty($_booking->tour_data)) {
    //             continue;
    //         }
    //         foreach ($_booking->tour_data as $_booking_data) {
    //             $adult = $_booking_data->form->form->adult;
    //             $child = $_booking_data->form->form->child;
    //             $infant = $_booking_data->form->form->infant;
    //             $adult_price = $_booking_data->tour->adult_price;
    //             $child_price = $_booking_data->tour->child_price;
    //             $infant_price = $_booking_data->tour->infant_price;
    //             $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
    //             $total += $total_tour_rate;
    //         }
    //     }
    //     // calculate transfer booking cost
    //     foreach ($bookings as $_booking) {
    //         if (empty($_booking->transfer_data)) {
    //             continue;
    //         }
    //         foreach ($_booking->transfer_data as $_booking_data) {
    //             $adult = $_booking_data->form->form->adult;
    //             $child = $_booking_data->form->form->child;
    //             $adult_price = $_booking_data->transfer->adult_price;
    //             $child_price = $_booking_data->transfer->child_price;
    //             $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
    //             $total += $total_transfer_rate;
    //         }
    //     }
    //     return $total;
    // }
    }
    public function getTotalWalletBalanceAttribute()
    {
        return $this->wallets()->sum('amount');
    }
    public function getAvailableWalletBalanceAttribute()
    {
        return ($this->getTotalWalletBalanceAttribute() + $this->getRefundBalanceAttribute()) + - $this->getTotalSpentAttribute();
    }
    public function getRefundBalanceAttribute()
    {
        $bookings = $this->bookings()->get();
        $cancellations = $this->cancellations()->get();
        $total = 0;
        foreach ($bookings as $_booking) {
            // add transfer refunds
            foreach ($_booking->transfer_data as $_transfer) {
                $booking_cancellation = $cancellations->where('booking_id', $_transfer->BookingId)->first();
                if ($booking_cancellation) {
                    $total += $booking_cancellation->value('charges');
                }
            }
            // add tour refunds
        //     foreach ($_booking->tour_data as $_tour) {
        //         $booking_cancellation = $cancellations->where('booking_id', $_tour->BookingId)->first();
        //         if ($booking_cancellation) {
        //             $total += $booking_cancellation->value('charges');
        //         }
        //     }
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