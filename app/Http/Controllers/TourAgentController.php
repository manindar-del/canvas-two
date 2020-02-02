<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Auth;
use View;
use Cache;

use App\Tour;
use App\City;
use App\Hotel;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Booking;
use App\Cancellation;
use App\User;
use App\Log;
use App\Setting;
use App\Symbol;
use Paginator;
use LengthAwarePaginator;


class TourAgentController extends Controller
{
    protected $errors = [];
    protected $form = [];
    protected $json = [];
    protected $search_id;
    protected $tours;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['auth']);
    }

    /**
     * Show tours search results page
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

        /*$all_hotels = [$data['json']->Hotels->Hotel];
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

        // dd($data);*/

        return view('tours.search.index', [
            'search_id' => $id,
            'title' => config('app.name'),
            'seo_meta' => '',
           // 'json' => $data['json'],
            'form' => $data['form'],
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
            'countries' => Country::orderBy('name', 'asc')->get(),
            'cities' => City::with(['country'])->orderBy('name', 'asc')->get(),
            'currencies' => Currency::orderBy('name', 'asc')->get(),

            //'min' => $min,
           // 'max' => $max,
           // 'available_cities' => $available_cities,
        ]);
    }

    /**
     * Search tours
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->check($request)->getData($request);
        $country =$request->country;
        $city =$request->city;

        $this->tours = Tour::where([
            ['country_id', '=', $country],
            ['city_id', '=', $city],
        ])->get();

        $data = session($this->search_id);
        $this->saveCache();

        //dd( $this->form );
        //$this->tours = Tour::get();
        //$symbol = Symbol::where('currency','sign');

        return view('agent.tours.index', [
            'search_id' => $this->search_id,
            'title' => config('app.name'),
            'seo_meta' => '',
            'tours' => $this->tours,
            'form' => $this->form,
            'transfer_countries' => $this->getCoutries(),
            'transfer_cities' => $this->getCities(),
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
            //'symbols' => Symbol::orderBy('sign', 'asc')->get(),
        ]);

    }

    /**
     * Show hotel
     *
     * @return Illuminate\Http\Response
     */
    public function show($search_id, $tour_id)
    {
        $this->form = session($search_id);

        $this->tours = Tour::findOrFail($tour_id);

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->description = 'Tour: ' . $this->tours->title;
        $log->search_type = 'tour';
        $log->search_item_id = $tour_id;
        $log->save();

        $setting = Setting::where('name','contract')->first();

        //dd( $data['form']);
        //$form = $data['form'];
        return view('agent.tours.show', [
            'title' => "",
            'seo_meta' => '',
            'tours' => $this->tours,
            'search_id' => $search_id,
            'tour_id' => $tour_id,
            'form' => $this->form,
            'contract_doc' => asset('storage/'.$setting->value),
        ]);
    }


   /**
     * Show Upload File
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $setting = Setting::where('name','contract')->first();

        return view('agent.home.hotelupload.create', [
            'title' => 'Hotel Contract Document',
            'tours' => $this->tours,
            'form' => $this->form,
            'contract_doc' => asset('storage/'.$setting->value),

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
    public function prebook(Request $request, $search_id, $tour_id)
    {
        // $data = Cache::get($search_id);

        $data = session($search_id);
         //dd($request->room);

        // get tour
        $tour_data = null;
        $this->tours = Tour::findOrFail($tour_id);
        $tour_data = $this->tours;

        $tour_cart = session('tour_cart');
        if (empty($tour_cart)) {
            $tour_cart = [];
        }
        $tour_cart[] = [
            'tour' => $tour_data,
            'form' => $data,
        ];

        $request->session()->put('tour_cart', $tour_cart);
        //dd($tour_cart);
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
        // dd(session('cart'));
        return view('agent.cart.index', [
            'title' => 'Cart',
            'seo_meta' => '',
            'tour_cart' => session('tour_cart'),
            'cart' => session('cart'),

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

        /*$cart = session('cart');
        unset($cart[$id]);
        session(['cart' => $cart]);
        */
        $tour_cart = session('tour_cart');
        unset($tour_cart[$id]);
        session(['tour_cart' => $tour_cart]);

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
        $booking_data = [];
        $members = $request->cart;
        $cart = $request->session()->get('cart');

        foreach ($cart as $index => $_item) {
            $hotel = $_item['hotel'];
            $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
            $RoomDetail = $PreBooking->RoomDetails->RoomDetail;

            // $all_members = [];
            // foreach ($members[$index] as $_hotel_group) {
            //     $all_members = array_merge($all_members, $_hotel_group);
            // }

            $booking_data[] = [
                'hotel' => $hotel,
                'PreBooking' => $PreBooking,
                'RoomDetail' => $RoomDetail,
                // 'members' => $all_members,
                'members' => $members[$index],
            ];
        }

        $bookings = [];
        // dd($booking_data);
        foreach ($booking_data as $_data) {
            $view = View::make('xml.book', $_data);
            $request_xml = $view->render();

            // dd($request_xml);

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'http://test.xmlhub.com/testpanel.php/action/bookhotel', [
                'form_params' => ['XML' => $request_xml]
            ]);

            $response_xml = simplexml_load_string($response->getBody(), null, LIBXML_NOCDATA);
            $bookings[] = json_decode(json_encode($response_xml));
        }

        $booking = Auth::user()->bookings()->save(Booking::create(['data' => $bookings]));

        $request->session()->forget('cart');
        $_booking = Booking::findOrFail($booking->id);

        foreach ($_booking->data as $_data) {
            if (
                !empty($_data->BookingDetails) &&
                !empty($_data->BookingDetails->BookingId)
            ) {
                $booking_data = $_data;
                break;
            }
        }
        //dd($booking_data->BookingDetails->BookingId);
        $message = "Your booking is completed.Your booking ID: ".$booking_data->BookingDetails->BookingId;
        $tel =  Auth::user()->tel;
        //http://sms.intlum.com/http-api.php?username=intlum&password=intlum456&senderid=TRIOMF&route=1&number=9163707255&message=hello
        $sms_url = 'http://sms.intlum.com/http-api.php?username=intlum&password=intlum456&senderid=TRIOMF&route=1&number='.$tel;
        $sms_url .= '&message='.$message;
        $response_sms = $client->request('GET', $sms_url);

        return redirect()->route('booking.show', [$booking->id]);
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
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $id
     * @param [type] $booking_id
     * @return void
     */
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
        $view = View::make('agent.booking.invoice_pdf',
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
            'tour_date' => ['required'],
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
            'city' => $request->city,
            'country' => $request->country,
            'tour_date' => $request->tour_date,
            'adult' => $request->adult,
            'child' => $request->child,
            'infant' => $request->infant,
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
    private function getTours()
    {
         $this->tours = Tour::get();
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
        // get booking
        $booking = Booking::findOrFail($id);

        // find the tour in the booking
        foreach ($booking->data as $_booking) {
            if ($_booking->BookingDetails->BookingId == $booking_id) {
                $booking_data = $_booking;
                break;
            }
        }

        die;

        // the tour was not found in the booking
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
     * Get countries
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getCoutries()
    {
        return  Country::whereIn('code', ['TH'])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get countries
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getCities()
    {
        return City::with(['country'])
            ->whereIn('name', ['Bangkok', 'Pattaya', 'Phuket', 'Krabi', 'Koh Samui', 'Hua Hin', 'Kanchanaburi', 'Others'])
            ->orderBy('name', 'asc')
            ->get();
    }

}
