<?php

namespace App\Http\Controllers;
use App\History;
use App\Log;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
     /**
     * Show
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('agent.home.index', [
            'title' => 'All Logs',
            'logs' => Log::latest()->take(4)->get(),
        ]);
    }
}
