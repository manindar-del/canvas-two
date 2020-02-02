<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use View;

use App\City;
use App\Hotel;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Booking;
use App\Cancellation;
use App\User;
use Illuminate\Validation\Rule;
use Auth;

use GuzzleHttp\Client;

class BookingController extends \App\Http\Controllers\Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $agents = Booking::orderBy('id', 'desc')->get();
        return view('admin.booking.index', [
            'title' => 'All booking',
            'seo_meta' => '',
            'all_booking' => Booking::orderBy('id', 'desc')->get(),
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
        ]);
    }

    /**
     * Update Booking Tour Status
     *
     * @return \Illuminate\Http\Response
     */
    public function bookingTourStatus(Request $request, $id, $booking_id)
    {
        $booking_tour_data = [];
        $booking = Booking::findOrFail($id);
        foreach ($booking->tour_data as $index => $_data) {
            if (
                !empty($_data->tour)
            ) {
                $booking_data = $_data;
                if( $booking_id == $_data->BookingId){
                    $BookingStatus = "Confirmed";
                 }else{
                    $BookingStatus = $booking_data->BookingStatus;
                 }
                 $booking_tour_data[] = [
                    'tour' => $booking_data->tour,
                    'form' => $booking_data->form,
                   'members' => $booking_data->members,
                    'BookingId' => $booking_data->BookingId,
                    'BookingStatus' => $BookingStatus,
                ];
               // break;
            }
        }
        $bookings_tour_insert = json_encode($booking_tour_data);
        /*echo "<pre>";
        dd($bookings_tour_insert);
        echo "</pre>";*/
        if(!empty($bookings_tour_insert)){
            $_bookings_tour_insert = $bookings_tour_insert;
            Booking::where('id', $id)->update(['tour_data' =>  $_bookings_tour_insert]);
        }
        return redirect()->back();

    }

    /**
     * Show all bookings for cuurent user
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function bookings(Request $request)
    {
        return view('admin.booking.index', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'all_booking' => Auth::user()->bookings()->orderBy('id', 'desc')->get(),
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
        ]);
    }

    /**
     * Update Booking Status
     *
     * @return \Illuminate\Http\Response
     */
    public function bookingUpdateStatus(Request $request, $id)
    {
       $booking = Booking::findOrFail($id);
       //$available_wallet_balance =  Auth::user()->available_wallet_balance;
       $user = User::findOrFail($booking->user_id);
       $available_wallet_balance = $user->available_wallet_balance;
       $total_amount = $booking->total_amount;
       //dd($available_wallet_balance);
        if(!empty($id) && $total_amount <  $available_wallet_balance){
            Booking::where('id', $id)->update(['payment_updated_type' =>  "Balance", "is_paid"=>"1"]);
            return redirect()
            ->back()
            ->with(['ok' => true, 'msg' => 'Payment successful using wallet']);
        }else{
            return redirect()
            ->back()
            ->with(['ok' => false, 'msg' => 'Insufficient balance in agents wallet']);
        }


    }
    /**
     * Show a specified booking
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showMyBookingByID(Request $request, $id)
    {
       $booking = Booking::findOrFail($id);
       $user_id = $booking->user_id;
       $cancellation = Cancellation::where('user_id', $user_id)->get();

      //dd($cancellation);
        return view('admin.booking.mybookingbyid_show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'all_booking' => $booking,
            'id' => $id,
            'cancellation' =>$cancellation,
        ]);
    }

    /**
     * Show a specified booking
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.booking.show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'booking' => $booking,
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
        ]);
    }

    /**
     * Show after tour  cancellation information
     *
     * @param int $id
     * @param string $booking_id
     * @return boolean
     */
    public function cancelTourIndex($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $cancellation = Cancellation::where('booking_id', $booking_id)->where('status', 'true')->first();
        $user_id = $booking->user_id;
        if ($cancellation) {
            return redirect()->route('booking.index');
        }
        foreach ($booking->tour_data as $_booking) {
            if ($_booking->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }
       if (empty($booking_data)) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }
       // $user_id = Auth::user()->id;
       //dd( $user_id);
        return view('admin.booking.cancel_tour', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'booking_id' => $booking_id,
            'user_id' => $user_id,

        ]);
    }

    /**
     * Show after cancellation information
     *
     * @param int $id
     * @param string $booking_id
     * @return boolean
     */
    public function cancelIndex($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $cancellation = Cancellation::where('booking_id', $booking_id)->where('status', 'true')->first();
        if ($cancellation) {
            return redirect()->route('booking.index');
        }
        foreach ($booking->data as $_booking) {
            if ($_booking->BookingDetails->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }
        if (empty($booking_data)) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }
        $policy = $this->getCancellationPolicyAfterBooking(
            $booking_data->BookingDetails->BookingId,
            $booking_data->BookingDetails->BookingCode
        );
        return view('admin.booking.cancel', [
            'booking' => $booking,
            'booking_id' => $booking_id,
            'policy' => $policy,
        ]);
    }


    /**
     * Show after transfer  cancellation information
     *
     * @param int $id
     * @param string $booking_id
     * @return boolean
     */
    public function cancelTransferIndex($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $cancellation = Cancellation::where('booking_id', $booking_id)->where('status', 'true')->first();
        $user_id = $booking->user_id;

        if ($cancellation) {
            return redirect()->route('booking.index');
        }

        foreach ($booking->transfer_data as $_booking) {
            if (!empty($_booking->BookingId) && $_booking->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        if (empty($booking_data)) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        return view('admin.booking.cancel_transfer', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'booking_id' => $booking_id,
            'user_id' => $user_id,
        ]);
    }

     /**
     * Get cancellation policy after booking
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return object
     */
    private function getCancellationPolicyAfterBooking($booking_id, $booking_code)
    {
        $view = View::make('xml.cancellation-policy-after-booking', [
            'booking_id' => $booking_id,
            'booking_code' => $booking_code,
        ]);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/getCancellationPolicyAfterBooking', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        return json_decode(json_encode($xml));
    }

      /**
     * Cancel tour booing
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return boolean
     */
    public function cancelTourUpdate($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $cancellation = Cancellation::where('booking_id', $booking_id)
            ->where('status', 'true')
            ->first();

        foreach ($booking->tour_data as $_booking) {
            if ($_booking->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        if (empty($booking_data)) {
            return redirect()->back()->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $tour_date =  date('Y-m-d',strtotime($booking_data->form->form->tour_date));
        } catch (\Exception $e) {
            $tour_date =  date('Y-m-d');
        }

        if ($cancellation) {
            return redirect()->back()->with([
                'ok' => false,
                'msg' => 'Booking already cancelled.',
            ]);
        }

        $tour_date = $booking_data->form->form->tour_date;
        $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($tour_date)));

        $adult = $booking_data->form->form->adult;
        $child = $booking_data->form->form->child;
        $infant = $booking_data->form->form->infant;
        $adult_price = $booking_data->tour->adult_price;
        $child_price = $booking_data->tour->child_price;
        $infant_price = $booking_data->tour->infant_price;
        $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);

        if (date('Y-m-d') <= $cancellation_last_day) {
            $cancellation_charge = $total_tour_rate;
        } else {
            $cancellation_charge = 0;
        }

        // create a cancellation for the tour
        $cancellation = Cancellation::create([
            'booking_id' => $booking_id,
            'status' => 'true',
        ]);

        // set cancellation data
        $cancellation->charges = $cancellation_charge;
        $cancellation->currency = 'IND';
        $cancellation->user_id = $booking->user_id;
        $cancellation->save();

        if ($booking->user) {
            try {
                Mail::to($booking->user)->send(new Cancelled($booking->user, $booking_data));
            } catch (\Exception $e) {
                //
            }
        }

        return redirect()->route('mybookingbyidbyadmin.show', [$id])->with([
            'ok' => true,
            'msg' => 'Tour Cancelled'
        ]);
    }

      /**
     * Cancel transfer booking
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return boolean
     */
    public function cancelTransferUpdate($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
    $cancellation = Cancellation::where('booking_id', $booking_id)
            ->where('status', 'true')
            ->first();

        foreach ($booking->transfer_data as $_booking) {
            if ($_booking->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        if (empty($booking_data)) {
            return redirect()->back()->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $transfer_date =  date('Y-m-d',strtotime($booking_data->form->form->transfer_date));
        } catch (\Exception $e) {
            $transfer_date =  date('Y-m-d');
        }

        if ($cancellation) {
            return redirect()->back()->with([
                'ok' => false,
                'msg' => 'Booking already cancelled.',
            ]);
        }

        $transfer_date = $booking_data->form->form->transfer_date;
        $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($transfer_date)));

        $adult = $booking_data->form->form->adult;
        $child = $booking_data->form->form->child;

        $adult_price = $booking_data->transfer->adult_price;
        $child_price = $booking_data->transfer->child_price;

        $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);

        if (date('Y-m-d') <= $cancellation_last_day) {
            $cancellation_charge = $total_transfer_rate;
        } else {
            $cancellation_charge = 0;
        }

        // create a cancellation for the transfer
        $cancellation = Cancellation::create([
            'booking_id' => $booking_id,
            'status' => 'true',
        ]);

        // set cancellation data
        $cancellation->charges = $cancellation_charge;
        $cancellation->currency = 'IND';
        $cancellation->user_id = $booking->user_id;
        $cancellation->save();

        if ($booking->user) {
            try {
                Mail::to($booking->user)->send(new Cancelled($booking->user, $booking_data));
            } catch (\Exception $e) {
                //
            }
        }

        return redirect()->route('mybookingbyidbyadmin.show', [$id])->with([
            'ok' => true,
            'msg' => 'Transfer Cancelled'
        ]);
    }

      /**
     * Cancel booing
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return boolean
     */
    public function cancelUpdate($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);

        // find the hotel booking
        foreach ($booking->data as $_booking) {
            if ($_booking->BookingDetails->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        // Booking was not found
        if (empty($booking_data)) {
            return redirect()->route('admin.booking.index')->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        // find the booking cost
        $booking_price = 0;
        if (!empty($booking_data->BookingDetails->BookingPrice)) {
            $prices = explode('|', $booking_data->BookingDetails->BookingPrice);
            // very important - apply hike on individual rooms
            foreach ($prices as $_price) {
                if (!empty($booking_data->hotel_hike)) {
                    $_price = ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_price, Auth::user(), $booking_data->hotel_hike));
                }
                $booking_price += ceil($_price);
            }
        }

        // cancel booking using API
        $policy = $this->getCancellation(
            $booking_data->BookingDetails->BookingId,
            $booking_data->BookingDetails->BookingCode
        );

        // create a new cancellation record
        $cancellation = Cancellation::create([
            'booking_id' => $policy->BookingId,
            'booking_code' => $policy->BookingCode,
            'status' => $policy->Status,
            'data' => $policy,
        ]);

        $cancellation->user_id = $booking->user_id;
        $cancellation->currency = 'INR';

        // booking was already cancelled - since cancellation cost is unknown
        // charge the complete booking cost
        if ('false' == $policy->Status) {
            if ('Already Cancelled' == $policy->Error) {
                $cancellation->charges = $booking_price;
                $cancellation->status = 'true';
                $cancellation->save();
            }
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('mybookingbyidbyadmin.show', [$id])->with([
                'ok' => false,
                'msg' => $policy->Error,
            ]);
        }

        // booking cancellation failed and the cancellation charge field is missing
        if (!empty($policy->Error) && empty($policy->CancellationCharges)) {
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('mybookingbyidbyadmin.show', [$id])->with([
                'ok' => false,
                'msg' => $policy->Error,
            ]);
        }

        // if the agent has price hike then apply the hike else apply normal charges
        if (!empty($booking_data->hotel_hike)) {
            $cancellation->charges = \App\Helpers\PriceHelper::getHikedHotelPrice($policy->CancellationCharges, Auth::user(), $booking_data->hotel_hike);
        } else {
            $cancellation->charges = $policy->CancellationCharges;
        }

        $cancellation->currency = $policy->Currency;
        $cancellation->user_id = $booking->user_id;
        $cancellation->save();

        return redirect()->route('bookingadmin.show', [$id, $booking_id])->with([
            'ok' => true,
            'msg' => 'Booking Cancelled'
        ]);
    }

    /**
     * Cancel booking
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return Illuminate\Http\
     */
    private function getCancellation($booking_id, $booking_code)
    {
        $view = View::make('xml.cancellation', [
            'booking_id' => $booking_id,
            'booking_code' => $booking_code,
        ]);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/cancelhotel', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        return json_decode(json_encode($xml));
    }

    /**
     * Display show invoice page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showTourInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;
        foreach ($booking->tour_data as $_data) {
            if (
                !empty($_data->tour) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }
        return view('admin.booking.tourinvoice', [
            'title' => 'Tour Booking Invoice',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'user' => $user,
            'created_at' => $created_at,
        ]);
    }
    /**
     * Display show Voucher page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showTourVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;
        foreach ($booking->tour_data as $_data) {
            if (
                !empty($_data->tour) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        $city_id = $booking_data->tour->city_id;
        $city = City::where('id', $city_id)->first();

        $country_id = $booking_data->tour->country_id;
        $country = Country::where('code', $country_id)->first();

        //dd($city->name);
        if (empty($booking_data)) {
            abort(404);
        }
        return view('admin.booking.tourvoucher', [
            'title' => 'Tour Booking Voucher',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'user' => $user,
            'created_at' => $created_at,
            'city' => $city->name,
            'country' => $country->name,
        ]);
    }

    /**
     * Display show voucher page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        //dd($booking->data);
        foreach ($booking->data as $_data) {
            if (
                !empty($_data->BookingDetails) &&
                !empty($_data->BookingDetails->BookingId) &&
                $booking_id == $_data->BookingDetails->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }
        $hotel_id = $booking_data->BookingRequest->Booking->HotelId;
        $hotel = Hotel::where('hotel_code', $hotel_id)->first(); //get data from hotel table
        return view('admin.booking.voucher', [
            'title' => 'Booking Voucher',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
             'id' => $id,
             'hotel' => $hotel,
        ]);
    }

     /**
     * Display show invoice page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        foreach ($booking->data as $_data) {
            if (
                !empty($_data->BookingDetails) &&
                !empty($_data->BookingDetails->BookingId) &&
                $booking_id == $_data->BookingDetails->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }
        $hotel_id = $booking_data->BookingRequest->Booking->HotelId;
        $hotel = Hotel::where('hotel_code', $hotel_id)->first(); //get data from hotel table
       // dd($booking_data->BookingDetails->BookingId);
        $created_at =  $booking->created_at;
        return view('admin.booking.invoice', [
            'title' => 'Booking Invoice',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'id' => $id,
           'created_at' => $created_at,
            'hotel' => $hotel,
            'user' => $user,

        ]);
    }
    /**
     * Display show voicher pdf page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
 */
    function pdfVoucher(Request $request, $id, $booking_id){
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        // dd($user->name);
        foreach ($booking->data as $_data) {
            if (
                !empty($_data->BookingDetails) &&
                !empty($_data->BookingDetails->BookingId) &&
                $booking_id == $_data->BookingDetails->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }
        $hotel_id = $booking_data->BookingRequest->Booking->HotelId;
        $hotel = Hotel::where('hotel_code', $hotel_id)->first(); //get data from hotel table
        // dd($booking->created_at);
        $created_at =  $booking->created_at;
        $view = View::make('admin.booking.voucher_pdf',
            ['booking' => $booking,
            'booking_data' => $booking_data,'id' => $id,
        'created_at' => $created_at,
            'hotel' => $hotel,
            'user' => $user,]);
        $pdf = $view->render();
        // Sends output inline to browser
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();

    }
    /**
     * Display show pdf page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    function pdfInvoice(Request $request, $id, $booking_id){
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
          // dd($user->name);
           foreach ($booking->data as $_data) {
               if (
                   !empty($_data->BookingDetails) &&
                   !empty($_data->BookingDetails->BookingId) &&
                   $booking_id == $_data->BookingDetails->BookingId
               ) {
                   $booking_data = $_data;
                   break;
               }
           }
           if (empty($booking_data)) {
               abort(404);
           }
           $hotel_id = $booking_data->BookingRequest->Booking->HotelId;
           $hotel = Hotel::where('hotel_code', $hotel_id)->first(); //get data from hotel table
          // dd($booking->created_at);
           $created_at =  $booking->created_at;
           $view = View::make('admin.booking.invoice_pdf',
            ['booking' => $booking,
            'booking_data' => $booking_data,'id' => $id,
           'created_at' => $created_at,
            'hotel' => $hotel,
            'user' => $user,]);
           $pdf = $view->render();
           // Sends output inline to browser
           $mpdf = new \Mpdf\Mpdf();
           $mpdf->WriteHTML($pdf);
           $mpdf->Output();

    }

    /**
     * Display show tour voicher pdf page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    function pdfTourVoucher(Request $request, $id, $booking_id){
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;
        foreach ($booking->tour_data as $_data) {
            if (!empty($_data->tour) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }
        $city_id = $booking_data->tour->city_id;
        $city = City::where('id', $city_id)->first();

        $country_id = $booking_data->tour->country_id;
        $country = Country::where('code', $country_id)->first();
        $view = View::make('admin.booking.tour_voucher_pdf',
        ['booking' => $booking,
        'booking_data' => $booking_data,
        'created_at' => $created_at,
        'city' => $city,
        'country' => $country,
        'user' => $user,]);
        $pdf = $view->render();
        // Sends output inline to browser
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();

    }
    /**
         * Display show invoice pdf page
         *
         * @param Request $request
         * @param int $id
         * @return Illuminate\Http\Response
     */
    function pdfTourInvoice(Request $request, $id, $booking_id){
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;
        foreach ($booking->tour_data as $_data) {
            if (!empty($_data->tour) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }
        if (empty($booking_data)) {
            abort(404);
        }

        $view = View::make('admin.booking.tour_invoice_pdf',
        ['booking' => $booking,
        'booking_data' => $booking_data,
        'created_at' => $created_at,
        'user' => $user,]);
        $pdf = $view->render();
        // Sends output inline to browser
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();

    }

    /**
     * Display transfer invoice
     *
     * @param Request $request
     * @param int $id booking id
     * @param int $string booking_id transfer booking id
     * @return Illuminate\Http\Response
     */
    public function showTransferInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->transfer) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        return view('admin.booking.transferinvoice', [
            'title' => 'Transfer Booking Invoice',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'user' => $user,
            'created_at' => $created_at,
        ]);
    }

    /**
     * Display transfer voicher
     *
     * @param Request $request
     * @param int $id booking id
     * @param int $string booking_id transfer booking id
     * @return Illuminate\Http\Response
     */
    public function showTransferVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user = User::findOrFail($user_id);
        $created_at =  $booking->created_at;

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->transfer) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        $city_id = $booking_data->transfer->city_id;
        $city = City::where('id', $city_id)->first();

        $country_id = $booking_data->transfer->country_id;
        $country = Country::where('code', $country_id)->first();

        if (empty($booking_data)) {
            abort(404);
        }

        return view('admin.booking.transfervoucher', [
            'title' => 'Transfer Booking Voucher',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'user' => $user,
            'created_at' => $created_at,
            'city' => $city->name,
            'country' => $country->name,
        ]);
    }

    /**
     * Display show tour voicher pdf page
     *
     * @param Request $request
     * @param int $id booking id
     * @param int $string booking_id transfer booking id
     * @return Illuminate\Http\Response
     */
    function pdfTransferVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user = User::findOrFail($user_id);
        $created_at =  $booking->created_at;

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->transfer) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        $city_id = $booking_data->transfer->city_id;
        $city = City::where('id', $city_id)->first();

        $country_id = $booking_data->transfer->country_id;
        $country = Country::where('code', $country_id)->first();

        $view = View::make('admin.booking.transfer_voucher_pdf', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'created_at' => $created_at,
            'city' => $city,
            'country' => $country,
            'user' => $user,
        ]);

        $pdf = $view->render();
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
    }

    /**
     * Display show invoice pdf page
     *
     * @param Request $request
     * @param int $id booking id
     * @param int $string booking_id transfer booking id
     * @return Illuminate\Http\Response
     */
    function pdfTransferInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user_id = $booking->user_id;
        $user    = User::findOrFail($user_id);
        $created_at =  $booking->created_at;

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->transfer) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        $view = View::make('admin.booking.transfer_invoice_pdf', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'created_at' => $created_at,
            'user' => $user,
        ]);

        $pdf = $view->render();
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
    }

}
