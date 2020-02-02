<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\ExpressCheckout;
use View;
use Cache;

use App\City;
use App\Hotel;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Booking;
use App\BookingStatus;
use App\Cancellation;
use App\User;
use App\Log;

use Paginator;
use LengthAwarePaginator;

class HotelController extends Controller
{
    protected $errors = [];
    protected $form = [];
    protected $json = [];
    protected $search_id;
    protected $booking_status;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'redirect_if_admin']);
    }

     * Show hotel search results page
     *
     * @param Request $request
     * @param string $id search id
     * @return Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        // $data = Cache::get($id);
        $data = session($id);
        if (empty($data)) {
            return redirect()->route('home');
        }

        $all_hotels = [$data['json']->Hotels->Hotel];
        if (is_array($data['json']->Hotels->Hotel)) {
            $all_hotels = $data['json']->Hotels->Hotel;
        }

        $prices = [];
        foreach ($all_hotels as $_hotel) {
            $prices[] = ceil($_hotel->Price);
        }

        $min = min($prices);
        $max = max($prices);

        if (!empty($request->min) && !empty($request->max) && $request->min != $request->max) {
            $min = $request->min;
            $max = $request->max;
        }

        // cities
        $available_cities = [];
        foreach ($all_hotels as $_hotel) {
            if (!empty($_hotel->hotel) && !empty($_hotel->hotel->city)) {
                if (!empty($available_cities[$_hotel->hotel->city])) {
                    $available_cities[$_hotel->hotel->city]['count']++;
                } else {
                    $available_cities[$_hotel->hotel->city] = [
                        'name' => $_hotel->hotel->city,
                        'count' => 1,
                    ];
                }
            }
        }

        // filter city
        if (!empty($request->filter_city)) {
            foreach ($all_hotels as $key => $_hotel) {
                $found = false;
                if (!empty($_hotel->hotel) && !empty($_hotel->hotel->city) && is_string($_hotel->hotel->city)) {
                    foreach ($request->filter_city as $_city_name) {
                        if (false !== stripos($_hotel->hotel->city, $_city_name)) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        unset($all_hotels[$key]);
                    }
                }
            }
        }

        // filter name
        if (!empty($request->hotel_name)) {
            foreach ($all_hotels as $key => $_hotel) {
                if (false === stripos($_hotel->Name, $request->hotel_name)) {
                    unset($all_hotels[$key]);
                }
            }
        }

        // filter price
        if (!empty($request->price_min) && !empty($request->price_max)) {
            foreach ($all_hotels as $key => $_hotel) {
                if ($_hotel->Price > $request->price_max || $_hotel->Price < $request->price_min) {
                    unset($all_hotels[$key]);
                }
            }
        }

        // filer rating
        if (!empty($request->rating)) {
            foreach ($all_hotels as $key => $_hotel) {
                if (!in_array($_hotel->Rating, $request->rating)) {
                    unset($all_hotels[$key]);
                }
            }
        }

        // dd($all_hotels);

        $data['json']->Hotels->Hotel = $all_hotels;

        //dd($data);
        return view('agent.search.index', [
            'search_id' => $id,
            'title' => config('app.name'),
            'seo_meta' => '',
            'json' => $data['json'],
            'form' => $data['form'],
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
            'countries' => Country::orderBy('name', 'asc')->get(),
            'cities' => City::with(['country'])->orderBy('name', 'asc')->get(),
            'currencies' => Currency::orderBy('name', 'asc')->get(),
            'min' => $min,
            'max' => $max,
            'available_cities' => $available_cities,
        ]);
    }

    /**
     * Search hotels
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->check($request)
            ->getData($request)
            ->searchUsingApi()
            ->getErrors();

        if (count($this->errors)) {
            return redirect()->back()->with([
                'ok' => false,
                'errors' => collect($this->errors),
            ]);
        }

        $this->getHotels();
        $this->saveCache();

        return redirect()->route('hotels.index', [$this->search_id]);
    }

    /**
     * Show hotel
     *
     * @return Illuminate\Http\Response
     */
    public function show($search_id, $hotel_id)
    {
        $hotel = $this->getHotelFromApi($hotel_id);
        $data = session($search_id);


        if (empty($hotel) or empty($hotel->Hotels) || empty($data)) {
            return redirect()->route('hotels.index', [$search_id]);
        }

        $hotel_data = null;
        $all_hotels = [$data['json']->Hotels->Hotel];

        if (is_array($data['json']->Hotels->Hotel)) {
            $all_hotels = $data['json']->Hotels->Hotel;
        }

        foreach ($all_hotels as $_hotel) {
            if ($_hotel->Id == $hotel_id) {
                $hotel_data = $_hotel;
                break;
            }
        }

        if (empty($hotel_data)) {
            return redirect()->route('hotels.index', [$search_id]);
        }

        // dd($hotel_data);

        $this->getCancellationPolicies($hotel_data, $data['form']);

        // dd($hotel_data->RoomDetails->RoomDetail);

        $hotel = $hotel->Hotels;
        $form = $data['form'];

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->description = 'Hotel: ' . $hotel->HotelName;
        $log->search_type = 'hotel';
        $log->search_item_id = $hotel_id;
        $log->data = $hotel;
        $log->save();

        return view('agent.search.show', [
            'title' => $hotel->HotelName,
            'seo_meta' => '',
            'hotel' => $hotel,
            'form' => $form,
            'rooms' => $hotel_data->RoomDetails->RoomDetail,
            'search_id' => $search_id,
            'hotel_id' => $hotel_id,
        ]);
    }

    /**
     * Add to cart i.e. prebook hotel room
     *
     * @param string $search_id
     * @param string $hotel_id
     * @param string $booking_id
     * @return Illuminate\Http\Response
     */
    public function prebook(Request $request, $search_id, $hotel_id, $booking_id)
    {
        // $data = Cache::get($search_id);
        //dd($search_id);
        $data = session($search_id);
        $request->session()->put('search_id', $search_id);


        $hotel = $this->getHotelFromApi($hotel_id);

        // dd($data);

        if (empty($hotel) or empty($hotel->Hotels) || empty($data)) {
            return redirect()->route('hotels.index', [$search_id]);
        }

        // get hotel
        $hotel_data = null;
        $all_hotels = [$data['json']->Hotels->Hotel];
        if (is_array($data['json']->Hotels->Hotel)) {
            $all_hotels = $data['json']->Hotels->Hotel;
        }
        foreach ($all_hotels as $_hotel) {
            if ($_hotel->Id == $hotel_id) {
                $hotel_data = $_hotel;
                break;
            }
        }

        // no hotels found
        if (empty($hotel_data)) {
            return redirect()->route('hotels.index', [$search_id]);
        }

        // get room
        $room = null;
        foreach ($hotel_data->RoomDetails->RoomDetail as $_room) {
            if ($_room->BookingKey == $booking_id) {
                $room = $_room;
                break;
            }
        }

        // no room found
        if (empty($room)) {
            return redirect()->route('hotels.show', [$search_id, $hotel_id]);
        }

        // dd($room);

        // get cancellation policy
        $cancellation_policy = $this->getCancellationPolicyFromApi($room, $hotel_data, $data['form']);
        if (empty($cancellation_policy) || empty($cancellation_policy->CancellationInformations)) {
            return redirect()->route('hotels.show', [$search_id, $hotel_id]);
        }
        $room->cancellation_policy = $cancellation_policy->CancellationInformations;

        // prebook room
        //dd($data['json']->SearchSessionId);
        $prebook_data = $this->getPreBookPolicy($data['json'],$data['form'], $hotel_data, $room);

        // prebooking data missing
        if (empty($prebook_data)) {
            return redirect()->route('hotels.show', [$search_id, $hotel_id])->with([
                'ok' => false,
                'msg' => 'Hotel prices changed. Please book again',
            ]);
        }

        // prebooking error
        if (!empty($prebook_data->error)) {
            return redirect()->route('hotels.show', [$search_id, $hotel_id])->with([
                'ok' => false,
                'msg' => $prebook_data->error,
            ]);
        }

        // other prebooking error
        if (
            !empty($prebook_data->PreBookingDetails) &&
            !empty($prebook_data->PreBookingDetails->Status) &&
            'False' == $prebook_data->PreBookingDetails->Status
        ) {
            if (!empty($prebook_data->PreBookingDetails->Reason)) {
                $msg = $prebook_data->PreBookingDetails->Reason;
            }
            return redirect()->route('hotels.show', [$search_id, $hotel_id])->with([
                'ok' => false,
                'msg' => $msg,
            ]);
        }

        $cart = session('cart');
        if (empty($cart)) {
            $cart = [];
        }
        $cart[] = [
            'hotel' => $hotel_data,
            'prebook' => $prebook_data,
            'form' => $data['form'],
            'json'=>$data['json']
        ];
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    /**
     * View cart
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function cart(Request $request)
    {
        //dd($request);
        return view('agent.cart.index', [
            'title' => 'Cart',
            'seo_meta' => '',
            'cart' => session('cart'),
            'tour_cart' => session('tour_cart'),
            'transfer_cart' => session('transfer_cart'),
        ]);
    }

    /**
     * Empty cart contetn
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function emptyCart(Request $request)
    {
        $request->session()->forget('cart');
        $request->session()->forget('tour_cart');
        $request->session()->forget('transfer_cart');
        return redirect()->route('home.book-now');
    }

    /**
     * Remove cart item
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function removeCartItem(Request $request, $id)
    {
        $cart = session('cart');
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with([
            'ok' => true,
            'msg' => 'Cart item removed',
        ]);
    }

    /**
     * Checkout
     *
     * @return Illuminate\Http\Response
     */
    public function checkout()
    {
       // dd('abc');
        return view('agent.cart.checkout', [
            'title' => 'Checkout',
            'seo_meta' => '',
            'cart' => session('cart'),
            'tour_cart' => session('tour_cart'),
            'transfer_cart' => session('transfer_cart'),
        ]);
    }

    /**
     * Book
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function book(Request $request)
    {
        //dd($request);
//        $search_id = $request->session()->get('search_id');
//        $data = session($search_id);
       // dd($data);
        $booking_data = [];
        $booking_tour_data = [];
        $members = $request->cart;
        $cart = $request->session()->get('cart');
        $tour_cart = $request->session()->get('tour_cart');
        $transfer_cart = $request->session()->get('transfer_cart');
        $payment_type = $request->payment_type;
        $tour_grand_total = $request->tour_grand_total;
        $transfer_grand_total = $request->transfer_grand_total;
        $total_amount = 0;
        $total_hotel_amount =0;

        $members = $request->cart;
        $tour_members = $request->tour_cart;
        $transfer_members = $request->transfer_cart;

        $cart_special_requests = $request->cart_special_requests;
        $tour_special_requests = $request->tour_special_requests;
        $transfer_special_requests = $request->transfer_special_requests;

        //dd($data['json']);
        // dd($cart);

        if(!empty($cart)){

            foreach ($cart as $index => $_item) {
                $hotel = $_item['hotel'];
                $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
                $RoomDetail = $PreBooking->RoomDetails->RoomDetail;

                // $all_members = [];
                // foreach ($members[$index] as $_hotel_group) {
                //     $all_members = array_merge($all_members, $_hotel_group);
                // }

                $_item['form']['special_request'] = $cart_special_requests[$index];

                $booking_data[] = [
                    'hotel' => $hotel,
                    'PreBooking' => $PreBooking,
                    'RoomDetail' => $RoomDetail,
                    // 'members' => $all_members,
                    'members' => $members[$index],
                    'hotel_hike' => Auth::user()->hotel_hike,
                    'form' => $_item['form'],
                ];
            }

            $bookings = [];
            $bookings_raw_xml_data = [];

            foreach ($booking_data as $_data) {
                //dd($_data['PreBooking']->SearchSessionId);
                $view = View::make('xml.book', $_data);
                $request_xml = $view->render();

                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/bookhotel', [
                    'form_params' => ['XML' => $request_xml]
                ]);

                $bookings_raw_xml_data[] = (string) $response->getBody();
                $response_xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
                $response_xml->hotel_hike = Auth::user()->hotel_hike;
                $response_xml->special_request = $_data['form']['special_request'];
                $bookings[] = json_decode(json_encode($response_xml));
            }

            foreach ($bookings as $_data) {
                if(!empty($_data->BookingDetails->BookingPrice)){
                    $total_hotel_amount += $_data->BookingDetails->BookingPrice;
                   // dd($total_hotel_amount);
                }else{
                    $total_hotel_amount = 0;
                }

		 $_data->TermsAndConditions =$RoomDetail->TermsAndConditions;
            }
        }

        $bookings_raw_xml_data = json_encode($bookings_raw_xml_data);

        if(!empty($tour_cart)){
            foreach ($tour_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(),0,'',''),0,6);
                $tour =  $_item['tour'];
                $form =  $_item['form'];
                $BookingId = "TT".$time.date('my');

                $form['special_request'] = $tour_special_requests[$index];
                //dd($_item);

                $booking_tour_data[] = [
                    'tour' => $tour,
                    'form' => $form,
                    // 'members' => $members[$index],
                    'members' => $tour_members[$index],
                    'BookingId' => $BookingId,
                    'BookingStatus' => "Confirmed",
                ];
            }

            $bookings_tour_insert = json_encode($booking_tour_data);
        }

        // dd($transfer_cart);
        // die;

        if(!empty($transfer_cart)){
            foreach ($transfer_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(),0,'',''),0,6);
                $transfer =  $_item['transfer'];
                $form =  $_item['form'];
                $BookingId = "TT".$time.date('my');

                //dd($_item);
                $form['special_request'] = $transfer_special_requests[$index];

                $booking_transfer_data[] = [
                    'transfer' => $transfer,
                    'form' => $form,
                    // 'members' => $members[$index],
                    'members' => $transfer_members[$index],
                    'BookingId' => $BookingId,
                    'BookingStatus' => "Confirmed",
                ];
            }
            $bookings_transfer_insert = json_encode($booking_transfer_data);
        }

        // dd(json_decode($bookings_transfer_insert));
        // dd(json_decode($bookings_tour_insert));
        // dd(json_decode($bookings_transfer_insert));

        if ($payment_type == "Later") {
            $status = "Due";
        } else {
            $status = "Completed";
        }

        if($payment_type == "Later") {
            $is_paid = "";
        } else {
            $is_paid = 1;
        }

        if(!empty($bookings_tour_insert)){
            $_bookings_tour_insert = $bookings_tour_insert;
        } else {
            $_bookings_tour_insert = "";
        }

        if(!empty($bookings_transfer_insert)){
            $_bookings_transfer_insert = $bookings_transfer_insert;
        } else {
            $_bookings_transfer_insert = "";
        }

        // dd($_bookings_transfer_insert);

        if (!empty($bookings)) {
            $bookings_insert_data = $bookings;
        } else {
            $bookings_insert_data = "";
        }

        $total_amount = $tour_grand_total + $total_hotel_amount + $transfer_grand_total;

        if (Auth::user()->available_wallet_balance > $total_amount && $payment_type != "Later"){
            $booking = Auth::user()
                ->bookings()
                ->save(Booking::create([
                    'data' => $bookings_insert_data,
                    'bookings_raw_xml_data' => $bookings_raw_xml_data,
                    'tour_data' => $_bookings_tour_insert,
                    'transfer_data' => $_bookings_transfer_insert,
                    'payment_type'=>$payment_type,
                    'is_paid'=>$is_paid,
                    'total_amount' =>$total_amount
                ]));
            $booking_id = $booking->id;
            $request->session()->forget('cart');
            $request->session()->forget('tour_cart');
            $request->session()->forget('transfer_cart');
            $_booking_new = Booking::findOrFail($booking->id);
            $booking_id = crc32($booking->id);
            $message = "Your booking is completed.Your booking ID: ".$booking_id;
            //$tel =  Auth::user()->tel;
            //http://sms.intlum.com/http-api.php?username=intlum&password=intlum456&senderid=TRIOMF&route=1&number=9163707255&message=hello
            //$sms_url = 'http://sms.intlum.com/http-api.php?username=intlum&password=intlum456&senderid=TRIOMF&route=1&number='.$tel;
            // $sms_url .= '&message='.$message;
            // $response_sms = $client->request('GET', $sms_url);
            return redirect()->route('booking.show', [$booking->id]);
        } elseif($payment_type == "Later"){
            $booking = Auth::user()->bookings()->save(Booking::create([
                'data' => $bookings_insert_data,
                'tour_data' => $_bookings_tour_insert,
                'transfer_data' => $_bookings_transfer_insert,
                'payment_type'=>$payment_type,
                'is_paid'=>$is_paid,
                'total_amount' =>$total_amount
            ]));
            $booking_id = $booking->id;
            $request->session()->forget('cart');
            $request->session()->forget('tour_cart');
            $request->session()->forget('transfer_cart');
            $_booking_new = Booking::findOrFail($booking->id);
            $booking_id = crc32($booking->id);
            $message = "Your booking is completed.Your booking ID: ".$booking_id;
            return redirect()->route('booking.show', [$booking->id]);
        } else {
            // $request->session()->forget('cart');
            // $request->session()->forget('tour_cart');
            // $request->session()->forget('transfer_cart');
            return redirect()->back()->with(['ok' => false, 'msg' => 'Insufficient balance in your wallet']);
        }
    }

    /**
     * Show all bookings for cuurent user
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function bookings(Request $request)
    {
        return view('agent.booking.index', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'all_booking' => Auth::user()->bookings()->orderBy('id', 'desc')->get(),
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
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
        return view('agent.booking.show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'booking' => $booking,
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
        $available_wallet_balance =  Auth::user()->available_wallet_balance;
        $total_amount = $booking->total_amount;
        // dd($booking->total_amount);
        if(!empty($id) && $total_amount <  $available_wallet_balance){
            Booking::where('id', $id)->update(['payment_updated_type' =>  "Balance", "is_paid"=>1]);
            return redirect()
            ->back()
            ->with(['ok' => true, 'msg' => 'Payment successful using wallet']);
        } else {
            return redirect()
            ->back()
            ->with(['ok' => false, 'msg' => 'Insufficient balance in your wallet']);
        }
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
        return view('agent.booking.voucher', [
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
        $user = Auth::user();
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
       // dd($booking_data->BookingDetails->BookingId);
        $created_at =  $booking->created_at;
        return view('agent.booking.invoice', [
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
     $user = Auth::user();
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
        $view = View::make('agent.booking.voucher_pdf',
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
 * Check the request form data
 *
 * @param Request $request
 * @return void
 */
    private function check(Request $request)
    {
        $rules = [
            'nationality' => ['required'],
            'city' => ['required', 'exists:cities,id'],
            'check_in' => ['required', 'date_format:d/m/Y'],
            'check_out' => ['required', 'date_format:d/m/Y'],
        ];
        $this->validate($request, $rules);
        return $this;
    }

    /**
     * Get serach params from request
     *
     * @param Request $request
     * @return void
     */
    private function getData(Request $request)
    {
        $city = City::find($request->city);
        $this->form = [
            'nationality' => $request->nationality,
            'city' => $city->city_code,
            // 'city' => $request->city,
            // 'city' => $request->city,
            'country' => $city->country_code,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nights' => $request->nights,
            'rooms' => $request->rooms,
            'adult' => $request->adult,
            'child' => $request->child,
            'hotel_name' => $request->hotel_name,
            'star_rating' => $request->star_rating,
            'child_age' => $request->child_age,
            'currency' => $request->currency,
            'special_request' => $request->special_request,
        ];
        return $this;
    }

    /**
     * Search API
     *
     * @return this
     */
    private function searchUsingApi()
    {

        $view = View::make('xml.search', $this->form);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/findhotel', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        $this->json = json_decode(json_encode($xml));

        return $this;
    }

    /**
     * Get errors
     *
     * @return this
     */
    private function getErrors()
    {
        if (!empty($this->json->error)) {
            $this->errors[] = $this->json->error;
        }

        if (!empty($this->json->Hotels->Error)) {
            $this->errors[] = $this->json->Hotels->Error;
        }

        return $this;
    }

    /**
     * Set cache
     *
     * @param string $id
     * @return void
     */
    private function saveCache()
    {
        $this->search_id = hash('sha256', Auth::user()->id . (microtime(true) * 10000));
        session([
            $this->search_id => [
                'json' => $this->json,
                'form' => $this->form,
            ]
        ]);
        return $this;
    }

    /**
     * Find hotels in database or fetch from API
     *
     * @return this
     */
    private function getHotels()
    {
        $data = [$this->json->Hotels->Hotel];
        if (is_array($this->json->Hotels->Hotel)) {
            $data = $this->json->Hotels->Hotel;
        }
        foreach ($data as $key => $_entry) {
            $hotel = Hotel::where('hotel_code', $_entry->Id)->first();
            if (empty($hotel)) {
                // $hotel = $this->getHotelFromApi($_entry->Id);
            }
            if (!empty($hotel)) {
                $_entry->hotel = $hotel;
            }
        }
        return $this;
    }

    /**
     * Get hotel from database
     *
     * @param int $id
     * @return this
     */
    private function getHotelFromApi($id)
    {
        $key = 'hotel-' . $id;
        $data = session($key);

        if (!empty($data)) {
            return $data;
        }

        $view = View::make('xml.get-hotel', ['id' => $id]);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/gethoteldetails', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        $json = json_decode(json_encode($xml));

        if (empty($json->error) && empty($json->Error)) {
            // Cache::put($key, $json, config('cache.time'));
            session([$key => $json]);
            return $json;
        }
    }

    /**
     * Get cancellation policies for rooms
     *
     * @param object $hotel_data
     * @param array $form_data
     * @return void
     */
    private function getCancellationPolicies($hotel_data, $form_data)
    {
        if (!is_array($hotel_data->RoomDetails->RoomDetail)) {
            $hotel_data->RoomDetails->RoomDetail = [$hotel_data->RoomDetails->RoomDetail];
        }
        foreach ($hotel_data->RoomDetails->RoomDetail as $key => $_room) {
            $_room->cancellation_policy = $this->getCancellationPolicyFromApi($_room, $hotel_data, $form_data);
        }
    }

    /**
     * Get cancellation policy from API
     *
     * @param object $room
     * @param object $hotel_data
     * @param array $form_data
     * @return object
     */
    private function getCancellationPolicyFromApi($room, $hotel_data, $form_data)
    {
        $view = View::make('xml.cancellation-policy', [
            'form' => $form_data,
            'hotel' => $hotel_data,
            'room' => $room,
        ]);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/getcancellationpolicy', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        return json_decode(json_encode($xml));
    }

    /**
     * Get PreBook policy for selected room
     *
     * @param object $hotel_data
     * @param array $form_data
     * @return void
     */
    private function getPreBookPolicy($json_data,$form_data, $hotel_data, $room_data)
    {
        //dd($json_data->SearchSessionId);
        $view = View::make('xml.prebook', [
            'form' => $form_data,
            'hotel' => $hotel_data,
            'room' => $room_data,
            'json_data'=>$json_data
        ]);
        $xml = $view->render();
        // dd($xml);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/prebook', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        return json_decode(json_encode($xml));
    }

    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Change currency
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function changeCurrency(Request $request)
    {
        $currency = Currency::find($request->currency);
        if ($currency) {
            $user = Auth::user();
            $user->currency = $currency->name;
            $user->save();
            if (!empty(session('cart'))) {
                $request->session()->flush();
                return redirect()->route('home');
            }
        }
        return redirect()->back();
    }

    /**
     * Show after cancellation information
     *
     * @param int $id
     * @param string $booking_id
     * @return boolean
     */
    public function cancelIndex(Request $request,$id, $booking_id)
    {
        $search_id = $request->session()->get('search_id');

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
            $booking_data->BookingDetails->BookingCode,
            $search_id
        );
        return view('agent.booking.cancel', [
            'booking' => $booking,
            'booking_id' => $booking_id,
            'policy' => $policy,
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
         $user_id = Auth::user()->id;
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
        $user_id = Auth::user()->id;
       //dd( $user_id);
        return view('agent.booking.cancel_tour', [
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
    private function getCancellationPolicyAfterBooking($booking_id, $booking_code,$search_id)
    {
        $data = session($search_id);
       // dd($data['json']->SearchSessionId);
        $view = View::make('xml.cancellation-policy-after-booking', [
            'booking_id' => $booking_id,
            'booking_code' => $booking_code,
            'json_data' =>$data['json']
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
     * Cancel booing
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return boolean
     */
    public function cancelUpdate(Request $request,$id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $search_id = $request->session()->get('search_id');

        foreach ($booking->data as $_booking) {
            if ($_booking->BookingDetails->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        // Booking was not found
        if (empty($booking_data)) {
            return redirect()->route('booking.index')->with([
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
            $booking_data->BookingDetails->BookingCode,
            $search_id
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
        //dd($policy->Status);
        // booking was already cancelled - since cancellation cost is unknown
        // charge the complete booking cost
        if ('false' == $policy->Status) {
            if ('Already Cancelled' == $policy->Error) {
                $cancellation->charges = $booking_price;
                $cancellation->status = 'true';
                $cancellation->save();
            }
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('booking.show', [$id])->with([
                'ok' => false,
                'msg' => $policy->Error,
            ]);
        }
        echo "abc";
        die;
        // booking cancellation failed and the cancellation charge field is missing
        if (!empty($policy->Error) && empty($policy->CancellationCharges)) {
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('booking.show', [$id])->with([
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
        $cancellation->save();

        return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
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
    private function getCancellation($booking_id, $booking_code,$search_id)
    {
        $data = session($search_id);

        $view = View::make('xml.cancellation', [
            'booking_id' => $booking_id,
            'booking_code' => $booking_code,
            'json_data' =>$data['json']
        ]);
        $xml = $view->render();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/cancelhotel', [
            'form_params' => ['XML' => $xml]
        ]);
        $xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        return json_decode(json_encode($xml));
    }




}
