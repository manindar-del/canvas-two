<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadTransferController extends Controller
{
    /**
     * Show upload contract form
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.upload.transfer.index');
    }

    /**
     * Handle contact form
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->check($request);
        $this->upload($request);
        return redirect()->back()->with([
            'message' => 'Successfully Upload File'
        ]);
    }

    /**
     * Validate incoming form data
     *
     * @param Request $request
     * @return void
     */
    private function check(Request $request)
    {
        $rules = [
            'transfer' => ['required', 'file']
        ];
        $request->validate($rules);
    }

    /**
     * Save uploaded file
     *
     * @param Request $request
     * @return void
     */
    private function upload(Request $request)
    {
        $setting = Setting::firstOrCreate([
            'name' => 'transfer'
        ]);
        $setting->value = $request->transfer->store('uplolads/transfers', 'public');
        $setting->save();
    }
}
