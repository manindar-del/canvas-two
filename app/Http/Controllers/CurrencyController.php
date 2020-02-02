<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\currency;
// use App\Session;
use Event;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    public function switch(Request $request)
    {

        $currency = 'INR';
        if (!empty($request->currency)) {
            $currency = $request->currency;


        }
         Session::put('currency', $currency);

        return redirect()->back();

    }
}