<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class UploadHotelContractController extends Controller
{
    /**
     * Show upload contract form
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.upload.hotel-contract.index', [
            'title' => 'Upload Hotel Contract Document',
        ]);
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
            'contract' => ['required', 'file']
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
            'name' => 'contract'
        ]);
        $setting->value = $request->contract->store('uplolads/contacts', 'public');
        $setting->save();
    }


}
