<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use View;
use Cache;
use Srmklive\PayPal\Services\ExpressCheckout;

use App\City;
use App\Hotel;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Booking;
use App\Cancellation;
use App\Mail\Cancelled;
use App\User;
use App\Payment as MasterPay;
use App\Mail\AdminBooking;
use App\Mail\AgentBooking;
use Illuminate\Support\Facades\Mail;
// use Instamojo\Instamojo;
use App\ConvertCurrency;
use Paginator;
use LengthAwarePaginator;



use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use Cart;

class HotelController extends Controller
{
    protected $errors = [];
    protected $form = [];
    protected $json = [];
    protected $search_id;

    // payment
    private $payment;
    private $api;
    private $apiPaymentResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

        public function __construct()
        {

            $this->middleware(['auth', 'redirect_if_admin']);


        }





    /**
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

        // dd($data);
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
        return view('paywithpaypal');

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
        $data = session($search_id);
        $hotel = $this->getHotelFromApi($hotel_id);
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

        // get cancellation policy
        $cancellation_policy = $this->getCancellationPolicyFromApi($room, $hotel_data, $data['form']);
        if (empty($cancellation_policy) || empty($cancellation_policy->CancellationInformations)) {
            return redirect()->route('hotels.show', [$search_id, $hotel_id]);
        }
        $room->cancellation_policy = $cancellation_policy->CancellationInformations;

        // prebook room
        $prebook_data = $this->getPreBookPolicy($data['form'], $hotel_data, $room);

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
        $request->session()->flush();
        return redirect()->route('home');
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
        //dd($request->all());
        // $search_id = $request->session()->get('search_id');
        // $data = session($search_id);
        // dd($data);

        $bookings = [];
        $bookings_raw_xml_data = [];
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
        $total_hotel_amount = 0;

        $members = $request->cart;
        $tour_members = $request->tour_cart;
        $transfer_members = $request->transfer_cart;

        $cart_special_requests = $request->cart_special_requests;
        $tour_special_requests = $request->tour_special_requests;
        $transfer_special_requests = $request->transfer_special_requests;

        // if (!empty($cart)) {

        //     foreach ($cart as $index => $_item) {
        //         $hotel = $_item['hotel'];
        //         $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
        //         $RoomDetail = $PreBooking->RoomDetails->RoomDetail;

        //         // $all_members = [];
        //         // foreach ($members[$index] as $_hotel_group) {
        //         //     $all_members = array_merge($all_members, $_hotel_group);
        //         // }

        //         $_item['form']['special_request'] = $cart_special_requests[$index];

        //         $booking_data[] = [
        //             'hotel' => $hotel,
        //             'PreBooking' => $PreBooking,
        //             'RoomDetail' => $RoomDetail,
        //             // 'members' => $all_members,
        //             'members' => $members[$index],
        //             'hotel_hike' => Auth::user()->hotel_hike,
        //             'form' => $_item['form'],
        //         ];
        //     }

        //     foreach ($booking_data as $_data) {
        //         //dd($_data['PreBooking']->SearchSessionId);
        //         $view = View::make('xml.book', $_data);
        //         $request_xml = $view->render();

        //         $client = new \GuzzleHttp\Client();
        //         $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/bookhotel', [
        //             'form_params' => ['XML' => $request_xml]
        //         ]);

        //         $bookings_raw_xml_data[] = (string) $response->getBody();
        //         $response_xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        //         $response_xml->hotel_hike = Auth::user()->hotel_hike;
        //         $response_xml->special_request = $_data['form']['special_request'];
        //         $bookings[] = json_decode(json_encode($response_xml));
        //     }

        //     foreach ($bookings as $_data) {
        //         if (!empty($_data->BookingDetails->BookingPrice)) {
        //             $total_hotel_amount += $_data->BookingDetails->BookingPrice;
        //             // dd($total_hotel_amount);
        //         } else {
        //             $total_hotel_amount = 0;
        //         }

        //         $_data->TermsAndConditions = $RoomDetail->TermsAndConditions;
        //     }
        // }

        $bookings_raw_xml_data = json_encode($bookings_raw_xml_data);

        if (!empty($tour_cart)) {
            foreach ($tour_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $tour =  $_item['tour'];
                $form =  $_item['form'];
                $BookingId = "TT" . $time . date('my');

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

        if (!empty($transfer_cart)) {
            foreach ($transfer_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $transfer =  $_item['transfer'];
                $form =  $_item['form'];
                $BookingId = "TT" . $time . date('my');

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

        if (!empty($bookings_tour_insert)) {
            $_bookings_tour_insert = $bookings_tour_insert;
        } else {
            $_bookings_tour_insert = "";
        }

        if (!empty($bookings_transfer_insert)) {
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

         // pay using wallet balance
         if (!empty($request->pay_walltet)) {
            if (Auth::user()->available_wallet_balance < $total_amount) {
                return redirect()->back()->with([
                    'ok' => false,
                    'msg' => 'Insufficient balance in your wallet'
                ]);
            }
            $payment_type = 'wallet';
            $booking = Auth::user()
                ->bookings()
                ->save(Booking::create([
                    'data' => $bookings_insert_data,
                    'bookings_raw_xml_data' => $bookings_raw_xml_data,
                    'tour_data' => $_bookings_tour_insert,
                    'transfer_data' => $_bookings_transfer_insert,
                    'payment_type' => $payment_type,
                    'is_paid' => true,
                    'total_amount' => $total_amount
                ]));
                $admin = User::find(1);
                $agent = Auth::user();
               Mail::to($admin)->send(new AdminBooking($agent));
               Mail::to($agent)->send(new AgentBooking($agent));

            $request->session()->forget('cart');
            $request->session()->forget('tour_cart');
            $request->session()->forget('transfer_cart');

            return redirect()->route('booking.show', [$booking->id]);

        }

        // pay online

        $payment_type = 'online';
        $booking = Auth::user()
            ->bookings()
            ->save(Booking::create([
                'data' => $bookings_insert_data,
                'bookings_raw_xml_data' => $bookings_raw_xml_data,
                'tour_data' => $_bookings_tour_insert,
                'transfer_data' => $_bookings_transfer_insert,
                'payment_type' => $payment_type,
                'is_paid' => false,
                'total_amount' => $total_amount
            ]));



        $this->addPayment($total_amount);
        $booking->payment_id = $this->payment->id;
        $booking->save();



        // return $this->initApi()->redirectToPaymentGateway();
    }



    /**
     * Show all bookings for cuurent user
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function bookings(Request $request)
    {
        $bookings = Auth::user()
            ->bookings()
            ->where('is_paid', true)
            ->orderBy('id', 'desc')->get();
        return view('agent.booking.index', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'all_booking' => $bookings,
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
        // dd($booking->toArray());
        return view('agent.booking.show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'booking' => $booking,
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
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

    function pdfInvoice(Request $request, $id, $booking_id)
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
        // dd($booking->created_at);
        $created_at =  $booking->created_at;
        $view = View::make(
            'agent.booking.invoice_pdf',
            [
                'booking' => $booking,
                'booking_data' => $booking_data, 'id' => $id,
                'created_at' => $created_at,
                'hotel' => $hotel,
                'user' => $user,
            ]
        );
        $pdf = $view->render();
        // Sends output inline to browser
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
    }
    function pdfVoucher(Request $request, $id, $booking_id)
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
        // dd($booking->created_at);
        $created_at =  $booking->created_at;
        $view = View::make(
            'agent.booking.voucher_pdf',
            [
                'booking' => $booking,
                'booking_data' => $booking_data, 'id' => $id,
                'created_at' => $created_at,
                'hotel' => $hotel,
                'user' => $user,
            ]
        );
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
    private function getPreBookPolicy($form_data, $hotel_data, $room_data)
    {
        $view = View::make('xml.prebook', [
            'form' => $form_data,
            'hotel' => $hotel_data,
            'room' => $room_data,
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
        return view('agent.booking.cancel', [
            'booking' => $booking,
            'booking_id' => $booking_id,
            'policy' => $policy,
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
     * Cancel booing
     *
     * @param string $booking_id
     * @param string $booking_code
     * @return boolean
     */
    public function cancelUpdate($id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        foreach ($booking->data as $_booking) {
            if ($_booking->BookingDetails->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }
        if (empty($booking_data)) {
            return redirect()->route('booking.index')->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        $policy = $this->getCancellation(
            $booking_data->BookingDetails->BookingId,
            $booking_data->BookingDetails->BookingCode
        );

        $cancellation = Cancellation::create([
            'booking_id' => $policy->BookingId,
            'booking_code' => $policy->BookingCode,
            'status' => $policy->Status,
            'data' => $policy,
        ]);
        if (!empty($policy->Error)) {
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('booking.show', [$id])->with([
                'ok' => false,
                'msg' => $policy->Error,
            ]);
        }
        if ('false' == $policy->Status) {
            if ('Already Cancelled' == $policy->Error) {
                $cancellation->status = 'true';
                $cancellation->save();
            }
            // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
            return redirect()->route('booking.show', [$id])->with([
                'ok' => false,
                'msg' => $policy->Error,
            ]);
        }

        $cancellation->charges = $policy->CancellationCharges;
        $cancellation->currency = $policy->Currency;
        $cancellation->user_id = Auth::user()->id;
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
     * Show a specified booking
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showMyBookingByID(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $user_id = Auth::user()->id;
        $cancellation = Cancellation::where('user_id', $user_id)->get();

        //dd($cancellation);
        return view('agent.booking.mybookingbyid_show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'all_booking' => $booking,
            'id' => $id,
            'cancellation' => $cancellation,
        ]);
    }

    /**
     * Show a specified booking
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function showMyBooking(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);

        foreach ($booking->tour_data as $_data) {
            if (
                !empty($_data->tour) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                //dd($booking_data);
                break;
            }
        }

        foreach ($booking->transfer_data as $_data) {
            if (
                !empty($_data->transfer) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                //dd($booking_data);
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        return view('agent.booking.mybooking_show', [
            'title' => 'Booking Info',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'booking_id' => $booking_id,
            'cancelllations' => Cancellation::all()->keyBy('booking_id'),
        ]);
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
        $user = Auth::user();
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
        return view('agent.booking.tourinvoice', [
            'title' => 'Tour Booking Invoice',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'user' => $user,
            'created_at' => $created_at,
        ]);
    }

    /**
     * Display show invoice pdf page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    function pdfTourInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();
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

        $view = View::make('agent.booking.tour_invoice_pdf', [
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
        $user = Auth::user();
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

        return view('agent.booking.tourvoucher', [
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
     * Display show tour voicher pdf page
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    function pdfTourVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();
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

        $city_id = $booking_data->tour->city_id;
        $city = City::where('id', $city_id)->first();

        $country_id = $booking_data->tour->country_id;
        $country = Country::where('code', $country_id)->first();
        $view = View::make('agent.booking.tour_voucher_pdf', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'created_at' => $created_at,
            'city' => $city,
            'country' => $country,
            'user' => $user,
        ]);

        $pdf = $view->render();
        // Sends output inline to browser
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
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
        $user_id = Auth::user()->id;
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
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $tour_date =  date('Y-m-d',strtotime($booking_data->form->form->tour_date));
        } catch (\Exception $e) {
            $tour_date =  date('Y-m-d');
        }

        if ($cancellation || date('Y-m-d') >= $tour_date) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Cancellation is not available.',
            ]);
        }

        return view('agent.booking.cancel_tour', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'booking_id' => $booking_id,
            'user_id' => $user_id,
            'cancelllations' => Cancellation::all(),
        ]);
    }

    /**
     * Cancel tour booking
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
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $tour_date =  date('Y-m-d',strtotime($booking_data->form->form->tour_date));
        } catch (\Exception $e) {
            $tour_date =  date('Y-m-d');
        }

        if ($cancellation || date('Y-m-d') >= $tour_date) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Cancellation is not available.',
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
        $cancellation->user_id = Auth::user()->id;
        $cancellation->save();

        Mail::to(Auth::user())->send(new Cancelled(Auth::user(), $booking_data));

        // return redirect()->route('booking.cancel.index', [$id, $booking_id])->with([
        return redirect()->route('mybookingbyid.show', [$id])->with([
            'ok' => true,
            'msg' => 'Booking Cancelled'
        ]);
    }

    /**
     * Common set of functions for canelling a tour
     *
     * @return
     */
    private function cacelTourCommon()
    {
        # code...
    }

    /**
     * Payment gateway callback
     *
     * @param Request $request
     * @param string $txn_id
     * @return Illuminate\Http\Response
     */
    public function callback(Request $request, $txn_id)
    {
        $this->payment = MasterPay::where('txn_id', $txn_id)->firstOrFail();
        $booking = Booking::where('payment_id', $this->payment->id)->firstOrFail();

        // if (!$this->isPaymentComplete($request->payment_request_id)) {
        //     return redirect()->route('cart.checkout')->with([
        //         'ok' => false,
        //         'msg' => 'Payment Failed! Please try again.',
        //     ]);
        // }

          $admin = User::find(1);
          $agent = Auth::user();
         Mail::to($admin)->send(new AdminBooking($agent));
         Mail::to($agent)->send(new AgentBooking($agent));

        $this->completePayment();
        $booking->is_paid = true;
        $booking->save();

        $request->session()->forget('cart');
        $request->session()->forget('tour_cart');
        $request->session()->forget('transfer_cart');

        return redirect()->route('booking.show', [$booking->id]);
    }

    /**
     * Create a new payment and set status to pending
     *
     * @param float $amount
     * @return Illuminate\Http\Response
     */
    private function addPayment($price)
    {
        $this->payment = MasterPay::create([
            'amount' => $price,
            'status' => 'pending',
            'user_id' => Auth::user()->id,
        ]);
        $this->payment->txn_id = Hash('sha256', $this->payment->id);
        $this->payment->request = $this->getPaymentData();
        $this->payment->save();
    }

    public function getAdultPriceAttribute($adult_price)
    {
        $user = Auth::user();
        $price = $adult_price;

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
    public function getChildPriceAttribute($child_price)
    {
     $user = Auth::user();
     $price = $child_price;

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

    public function paywithpaypal(Request $request)
    {


        //dd($request->all());
        // $search_id = $request->session()->get('search_id');
        // $data = session($search_id);
        // dd($data);

        $bookings = [];
        $bookings_raw_xml_data = [];
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
        $total_hotel_amount = 0;

        $members = $request->cart;
        $tour_members = $request->tour_cart;
        $transfer_members = $request->transfer_cart;

        $cart_special_requests = $request->cart_special_requests;
        $tour_special_requests = $request->tour_special_requests;
        $transfer_special_requests = $request->transfer_special_requests;

        // if (!empty($cart)) {

        //     foreach ($cart as $index => $_item) {
        //         $hotel = $_item['hotel'];
        //         $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
        //         $RoomDetail = $PreBooking->RoomDetails->RoomDetail;

        //         // $all_members = [];
        //         // foreach ($members[$index] as $_hotel_group) {
        //         //     $all_members = array_merge($all_members, $_hotel_group);
        //         // }

        //         $_item['form']['special_request'] = $cart_special_requests[$index];

        //         $booking_data[] = [
        //             'hotel' => $hotel,
        //             'PreBooking' => $PreBooking,
        //             'RoomDetail' => $RoomDetail,
        //             // 'members' => $all_members,
        //             'members' => $members[$index],
        //             'hotel_hike' => Auth::user()->hotel_hike,
        //             'form' => $_item['form'],
        //         ];
        //     }

        //     foreach ($booking_data as $_data) {
        //         //dd($_data['PreBooking']->SearchSessionId);
        //         $view = View::make('xml.book', $_data);
        //         $request_xml = $view->render();

        //         $client = new \GuzzleHttp\Client();
        //         $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/bookhotel', [
        //             'form_params' => ['XML' => $request_xml]
        //         ]);

        //         $bookings_raw_xml_data[] = (string) $response->getBody();
        //         $response_xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
        //         $response_xml->hotel_hike = Auth::user()->hotel_hike;
        //         $response_xml->special_request = $_data['form']['special_request'];
        //         $bookings[] = json_decode(json_encode($response_xml));
        //     }

        //     foreach ($bookings as $_data) {
        //         if (!empty($_data->BookingDetails->BookingPrice)) {
        //             $total_hotel_amount += $_data->BookingDetails->BookingPrice;
        //             // dd($total_hotel_amount);
        //         } else {
        //             $total_hotel_amount = 0;
        //         }

        //         $_data->TermsAndConditions = $RoomDetail->TermsAndConditions;
        //     }
        // }

        $bookings_raw_xml_data = json_encode($bookings_raw_xml_data);

        if (!empty($tour_cart)) {
            foreach ($tour_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $tour =  $_item['tour'];
                $form =  $_item['form'];
                $BookingId = "TT" . $time . date('my');

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

        if (!empty($transfer_cart)) {
            foreach ($transfer_cart as $index => $_item) {
                $time =  substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $transfer =  $_item['transfer'];
                $form =  $_item['form'];
                $BookingId = "TT" . $time . date('my');

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

        if (!empty($bookings_tour_insert)) {
            $_bookings_tour_insert = $bookings_tour_insert;
        } else {
            $_bookings_tour_insert = "";
        }

        if (!empty($bookings_transfer_insert)) {
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

         // pay using wallet balance
         if (!empty($request->pay_walltet)) {
            if (Auth::user()->available_wallet_balance < $total_amount) {
                return redirect()->back()->with([
                    'ok' => false,
                    'msg' => 'Insufficient balance in your wallet'
                ]);
            }
            $payment_type = 'wallet';
            $booking = Auth::user()
                ->bookings()
                ->save(Booking::create([
                    'data' => $bookings_insert_data,
                    'bookings_raw_xml_data' => $bookings_raw_xml_data,
                    'tour_data' => $_bookings_tour_insert,
                    'transfer_data' => $_bookings_transfer_insert,
                    'payment_type' => $payment_type,
                    'is_paid' => true,
                    'total_amount' => $total_amount
                ]));
                $admin = User::find(1);
                $agent = Auth::user();
               Mail::to($admin)->send(new AdminBooking($agent));
               Mail::to($agent)->send(new AgentBooking($agent));

            $request->session()->forget('cart');
            $request->session()->forget('tour_cart');
            $request->session()->forget('transfer_cart');

            return redirect()->route('booking.show', [$booking->id]);

        }

        // pay online

        $payment_type = 'online';
        $booking = Auth::user()
            ->bookings()
            ->save(Booking::create([
                'data' => $bookings_insert_data,
                'bookings_raw_xml_data' => $bookings_raw_xml_data,
                'tour_data' => $_bookings_tour_insert,
                'transfer_data' => $_bookings_transfer_insert,
                'payment_type' => $payment_type,
                'is_paid' => false,
                'total_amount' => $total_amount
            ]));

            $this->addPayment($total_amount);
            $booking->payment_id = $this->payment->id;
            $booking->save();

///////////
    //   $transfer_cart = $request->session()->get('transfer_cart');

    //   $price= 0;
    // //   dd($transfer_cart);
    //   foreach($transfer_cart as $key => $value){
    //     // dd($value);

    //     $adult = $value['form']['form']['adult'] * $value['transfer']['adult_price'];
    //     $child = $value['form']['form']['child'] * $value['transfer']['child_price'];
    //     $price = $price + $adult + $child;

    //   }


        $data = [];
        $data['items'] = [
          [
              'name' =>Auth::user()->name,
              'price' =>$total_amount,

              'qty' => 1,
          ]
        ];


        $data['invoice_id'] = mt_rand();
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('cart.callback', [$this->payment->txn_id]);
        $data['cancel_url'] = route('payment.cancel');
        $data['total'] = $total_amount;




        $provider = new ExpressCheckout;

        $response = $provider->setExpressCheckout($data);

        $response = $provider->setExpressCheckout($data, true);

        return redirect($response['paypal_link']);
    }

/**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        //dd('Your payment is canceled. You can create cancel page here.');
        $this->payment = \App\Payment::create([
            'amount' => $price,
            'status' => 'pending',
            'user_id' => Auth::user()->id,
        ]);
        $this->payment->txn_id = Hash('sha256', $this->payment->id);
        //$this->payment->request = $this->data();
        $this->payment->save();
    }

    /**
     * Show a specified booking
     *
     * @param Request $request
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function success(Request $request)
    {
        // $booking = Booking::findOrFail($id);
        $transfer_cart = $request->session()->get('transfer_cart');
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);

        $price= 0;

          foreach($transfer_cart as $key => $value){

            $adult = $value['form']['form']['adult'] * $value['transfer']['adult_price'];
            $child = $value['form']['form']['child'] * $value['transfer']['child_price'];
            $price = $price + $adult + $child;
          }

        if ($response['ACK']== 'Success') {


            $this->payment = \App\Payment::create([
                 'amount' =>$price,
                 'user_id' => Auth::user()->id,
                ]);

            $this->payment->status = 'success';
            $this->payment->txn_id = Hash('sha256', $this->payment->id);
            $this->payment->request = $this->getPaymentData();
            $this->payment->save();




            $this->callback($request ,$this->payment->txn_id);


            // return redirect()->route('booking.show', ['id'=>$id])->with([
            //         'ok' => true,
            //         'msg' => 'Booking success'
            //     ]);


        // return redirect()->route('cart.callback',  [$this->payment->txn_id])->with([
        //         'ok' => true,
        //         'msg' => 'Booking success'
        //     ]);

            // return redirect()->route('mybookingbyid.show', [$id])->with([
            //     'ok' => true,
            //     'msg' => 'Booking success'
            // ]);
        }


        //dd('Something is wrong.');
    }

    /**
     * Get payment data
     *
     * @return array
     */
      private function getPaymentData()
      {

         return [
             'purpose'=>'Payment Online',
              'user'=>Auth::user()->name,

         ];


 }



        /**
         * Create a new payment and set status to pending
         *
         * @return Illuminate\Http\Response
         */
//         private function redirectToPaymentGateway(Request $request)
//         {
//       try {
//             $response =  $this->api->paymentRequestCreate($this->getPaymentData());
//             return redirect($response['longurl']);
//         } catch (\Exception $e) {
//             return redirect()->back()->with(['ok' => false, 'msg' => $e->getMessage()]);

//   }

//         }
  /**
     * Mark payment as complete and save server response
     *
     * @return void
     */
    private function completePayment()
    {
        // $this->payment->response = $this->apiPaymentResponse;
        $this->payment->status = 'success';
        $this->payment->save();
    }

    /**
     * Initialize API
     *
     * @return this
     */
    // private function initApi()
    // {
    //     // [
    //     //     'client_id' => env('PAYPAL_CLIENT_ID',''),
    //     //     'secret' => env('PAYPAL_SECRET',''),
    //     //     'settings' => array(
    //     //         'mode' => env('PAYPAL_MODE','sandbox'),
    //     //         'http.ConnectionTimeOut' => 30,
    //     //         'log.LogEnabled' => true,
    //     //         'log.FileName' => storage_path() . '/logs/paypal.log',
    //     // //         'log.LogLevel' => 'ERROR'
    //     //     ),
    //     // ];

    //     // return $this;
    // }

    /**
     * Check if payment was successful
     *
     * @param string $payment_request_id - API Payment request id
     * @return boolean
     */
    private function isPaymentComplete($payment_request_id)
    {
        $this->initApi();
        try {
            $respnose = $this->api->paymentRequestStatus($payment_request_id);
            if ('Completed' == $respnose['status'] && $respnose['amount'] == $this->payment->amount) {
                $this->apiPaymentResponse = $respnose;
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

}
