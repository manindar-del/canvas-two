<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return view('agent.home.payment.index', [
            'title' => 'Payments',
            'payments' => Auth::user()->wallets,
        ]);
    }
}
