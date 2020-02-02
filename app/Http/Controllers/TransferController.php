<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Auth;
use View;
use Cache;

use App\Transfer;
use App\City;
use App\Hotel;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Booking;
use App\Cancellation;
use App\User;
use App\Log;
use App\Mail\Cancelled;
use App\Setting;
use Illuminate\Support\Facades\Mail;
use Paginator;
use LengthAwarePaginator;

class TransferController extends Controller
{
    protected $errors = [];
    protected $form = [];
    protected $json = [];
    protected $search_id;
    protected $transfers;

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
     * Show transfers search results page
     *
     * @param Request $request
     * @param string $id search id
     * @return Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $data = session($id);
        if (empty($data)) {
            return redirect()->route('home');
        }

        return view('transfers.search.index', [
            'search_id' => $id,
            'title' => config('app.name'),
            'seo_meta' => '',
            'form' => $data['form'],
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
            'countries' => Country::orderBy('name', 'asc')->get(),
            'cities' => City::with(['country'])->orderBy('name', 'asc')->get(),
            'currencies' => Currency::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Search transfers
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->check($request)->getData($request);
        $country =$request->country;
        $city = $request->city;
        $type = $request->type;

        $this->transfers = Transfer::where([
            ['country_id', $country],
            ['city_id', $city],
            ['type', $type],
        ])->get();

        $this->saveCache();

        return view('agent.transfers.index', [
            'search_id' => $this->search_id,
            'title' => config('app.name'),
            'seo_meta' => '',
            'transfers' => $this->transfers,
            'form' => $this->form,
            'transfer_countries' => $this->getCoutries(),
            'transfer_cities' => $this->getCities(),
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Show hotel
     *
     * @return Illuminate\Http\Response
     */
    public function show($search_id, $transfer_id)
    {

        $this->form = session($search_id);
        //dd($this->form);

        $this->transfers = Transfer::findOrFail($transfer_id);

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->description = 'Transfer: ' . $this->transfers->title;
        $log->search_type = 'transfer';
        $log->search_item_id = $transfer_id;
        $log->save();

        $setting = Setting::where('name','transfer')->first();
        //dd( $data['form']);
        //$form = $data['form'];
        return view('agent.transfers.show', [
            'title' => "",
            'seo_meta' => '',
            'transfers' => $this->transfers,
            'search_id' => $search_id,
            'transfer_id' => $transfer_id,
            'form' => $this->form,
            'tranfer_doc' => asset('storage/'.$setting->value),
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
    public function prebook(Request $request, $search_id, $transfer_id)
    {
        // $data = Cache::get($search_id);
        $data = session($search_id);

        // get transfer
        $transfer_data = null;
        $this->transfers = Transfer::findOrFail($transfer_id);
        $transfer_data = $this->transfers;

        $transfer_cart = session('transfer_cart');
        if (empty($transfer_cart)) {
            $transfer_cart = [];
        }

        $transfer_cart[] = [
            'transfer' => $transfer_data,
            'form' => $data,
        ];

        $request->session()->put('transfer_cart', $transfer_cart);
        //dd($transfer_cart);
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
            'transfer_cart' => session('transfer_cart'),
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
        $transfer_cart = session('transfer_cart');
        unset($transfer_cart[$id]);
        session(['transfer_cart' => $transfer_cart]);

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

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->BookingId) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        $created_at =  $booking->created_at;
        $city = City::find($booking_data->transfer->city_id);
        $country = Country::where('code', $booking_data->transfer->country_id)->first();

        return view('agent.booking.transfer-voucher', [
            'title' => 'Transfer Voucher',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'id' => $id,
            'created_at' => $created_at,
            'city' => $city,
            'country' => $country,
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
        foreach ($booking->transfer_data as $_data) {
            if (
                !empty($_data->BookingId) &&
                $booking_id == $_data->BookingId
            ) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        // $hotel_id = $booking_data->BookingRequest->Booking->HotelId;
        // $hotel = Hotel::where('hotel_code', $hotel_id)->first(); //get data from hotel table
       // dd($booking_data->BookingDetails->BookingId);
        $created_at =  $booking->created_at;
        $city = City::find($booking_data->transfer->city_id);
        $country = Country::where('code', $booking_data->transfer->country_id)->first();

        return view('agent.booking.transfer-invoice', [
            'title' => 'Transfer Invoice',
            'seo_meta' => '',
            'booking' => $booking,
            'booking_data' => $booking_data,
            'id' => $id,
            'created_at' => $created_at,
            'user' => $user,
            'city' => $city,
            'country' => $country,
        ]);
    }

    function pdfInvoice(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->BookingId) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        $created_at =  $booking->created_at;
        $city = City::find($booking_data->transfer->city_id);
        $country = Country::where('code', $booking_data->transfer->country_id)->first();

        $view = View::make('agent.booking.transfer-invoice-pdf', [
            'booking' => $booking,
            'booking_data' => $booking_data,'id' => $id,
            'created_at' => $created_at,
            'user' => $user,
            'city' => $city,
            'country' => $country,
        ]);

        $pdf = $view->render();
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
    }

    function pdfVoucher(Request $request, $id, $booking_id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        foreach ($booking->transfer_data as $_data) {
            if (!empty($_data->BookingId) && $booking_id == $_data->BookingId) {
                $booking_data = $_data;
                break;
            }
        }

        if (empty($booking_data)) {
            abort(404);
        }

        $created_at =  $booking->created_at;
        $city = City::find($booking_data->transfer->city_id);
        $country = Country::where('code', $booking_data->transfer->country_id)->first();

        $view = View::make('agent.booking.transfer-voucher-pdf', [
            'booking' => $booking,
            'booking_data' => $booking_data,'id' => $id,
            'created_at' => $created_at,
            'user' => $user,
            'city' => $city,
            'country' => $country,
        ]);

        $pdf = $view->render();
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
            // 'nationality' => ['required'],
            'city' => ['required', 'exists:cities,id'],
            'transfer_date' => ['required'],
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
            // 'nationality' => $request->nationality,
            'city' => $request->city,
            'country' => $request->country,
            'transfer_date' => $request->transfer_date,
            'adult' => $request->adult,
            'child' => $request->child,
            'type' => $request->type,
            // 'infant' => $request->infant,
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
         $this->transfers = Transfer::get();
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

    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Show after tour  cancellation information
     *
     * @param int $id
     * @param string $booking_id
     * @return boolean
     */
    public function cancelIndex($id, $booking_id)
    {
        $user_id = Auth::user()->id;
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
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $transfer_date =  date('Y-m-d',strtotime($booking_data->form->form->transfer_date));
        } catch (\Exception $e) {
            $transfer_date =  date('Y-m-d');
        }

        if ($cancellation || date('Y-m-d') >= $transfer_date) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Cancellation is not available.',
            ]);
        }

        return view('agent.booking.cancel-transfer', [
            'booking' => $booking,
            'booking_data' => $booking_data,
            'booking_id' => $booking_id,
            'user_id' => $user_id,
        ]);
    }

    /**
     * Cancel transfer booing
     *
     * @param string $id booking ID
     * @param string $booking_id transfer ID
     * @return boolean
     */
    public function cancelUpdate($id, $booking_id)
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
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Booking not found',
            ]);
        }

        try {
            $transfer_date =  date('Y-m-d',strtotime($booking_data->form->form->transfer_date));
        } catch (\Exception $e) {
            $transfer_date =  date('Y-m-d');
        }

        if ($cancellation || date('Y-m-d') >= $transfer_date) {
            return redirect()->back(route('booking.index'))->with([
                'ok' => false,
                'msg' => 'Cancellation is not available.',
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
            'title' => 'Upload Hotel Contract Document',
            'form' => $this->form,
            'contract_doc' => asset('storage/'.$setting->value),

        ]);
    }


}
