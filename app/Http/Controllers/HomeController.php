<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\City;
use App\Country;
use App\Nationality;
use App\Currency;
use App\Log;
use App\Setting;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'redirect_if_admin']);
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('template.index', [
            'title' => config('app.name'),
        ]);
    }

    public function book(Request $request)
    {
        $request->session()->forget('form');
        // $logs = Auth::user()->logs()->latest()->get()->keyBy(function($log) {
        //     return $log->search_type . $log->search_item_id;
        // })->take(4);
        //$setting = Setting::where('name','contract')->first();
        $logs = collect([]);
        return view('agent.home.index', [
            'title' => config('app.name'),
            'seo_meta' => '',
            'nationalities' => Nationality::orderBy('name', 'asc')->get(),
            'countries' => Country::orderBy('name', 'asc')->get(),
            'cities' => City::with(['country'])->orderBy('name', 'asc')->where('country_code', 'TH')->get(),
            'currencies' => Currency::orderBy('name', 'asc')->get(),
            'transfer_countries' => $this->getCoutries(),
            'transfer_cities' => $this->getCities(),
            'logs' => $logs,
            'title' => 'All Logs',
            'logs' => Log::latest()->take(4)->get(),
            //'contract_doc' => asset('storage/'.$setting->value),
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






}
