<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Cancellation;
use App\Mail\CancellationExpiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CancellationExpiryNotificationController extends Controller
{
    private $bookings;
    private $tours;
    private $transfers;
    private $cancellations;

    /**
     * Get all active tours and transfers for which cancellation is expiring
     * tomorrow and notify the agent via email
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke()
    {
        $this->getBookings()
            ->getCancellations()
            ->getTours()
            ->getTransfers()
            ->sendEmail();
    }

    /**
     * Get all bookings
     *
     * @return $this
     */
    private function getBookings()
    {
        $this->bookings = Booking::all();
        return $this;
    }

    /**
     * Get all cancellations
     *
     * @return $this
     */
    private function getCancellations()
    {
        $this->cancellations = Cancellation::with(['user'])->get();
        return $this;
    }

    /**
     * Get all tours for which cancellation is expiring
     * tomorrow
     *
     * @return $this
     */
    private function getTours()
    {
        $this->tours = [];
        foreach ($this->bookings as $_booking) {
            $tours = $_booking->tour_data;
            foreach ($tours as $_tour) {
                if ($this->isActiveAndShouldNotify($_tour, 'tour')) {
                    $this->tours[] = ['booking' => $_tour, 'user' => $_booking->user];
                }
            }
        }
        return $this;
    }

    /**
     * Get all transfers for which cancellation is expiring
     * tomorrow
     *
     * @return $this
     */
    private function getTransfers()
    {
        $this->transfers = [];
        foreach ($this->transfers as $_booking) {
            $transfers = $_booking->transfer_data;
            foreach ($transfers as $_transfer) {
                if ($this->isActiveAndShouldNotify($_transfer, 'transfer')) {
                    $this->transfers[] = ['booking' => $_transfer, 'user' => $_booking->user];
                }
            }
        }
        return $this;
    }

    /**
     * Notify via email
     *
     * @return $this
     */
    private function sendEmail()
    {
        foreach ($this->tours as $_tour) {
            Mail::to($_tour['user'])->send(new CancellationExpiry($_tour['user'], $_tour['booking']));
        }
        foreach ($this->transfers as $_transfer) {
            Mail::to($_transfer['user'])->send(new CancellationExpiry($_transfer['user'], $_transfer['booking']));
        }
    }

    /**
     * Check if booking is active and today is the day before cancellation is closed
     *
     * @param object $booking
     * @param string $type
     * @return boolean
     */
    private function isActiveAndShouldNotify($booking, $type)
    {
        $cancelled = false;
        if ('tour' == $type) {
            $target_date = $booking->form->form->tour_date;
        } elseif('transfer' == $type) {
            $target_date = $booking->form->form->transfer_date;
        }
        // $booking_date =  date('Y-m-d',strtotime($transfer_date));
        $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($target_date)));
        foreach ($this->cancellations as $_cancellation) {
            if ($booking->BookingId == $_cancellation['booking_id']) {
                $cancelled = true;
            }
        }
        return (!$cancelled && date('Y-m-d') <= $cancellation_last_day);
        // return !$cancelled;
    }
}
