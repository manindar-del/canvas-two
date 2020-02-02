<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Transfer;
use App\City;
use App\Country;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TransferController extends \App\Http\Controllers\Controller
{
    protected $transfer;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::get();
        return view('admin.transfers.index', [
            'title' => 'All Transfers',
            'seo_meta' => '',
            'transfers' => $transfers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transfers.create', [
            'title' => 'Add New Transfer',
            'seo_meta' => '',
            'country' => $this->getCoutries(),
            'cities' => $this->getCities(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->transfer = new Transfer;
        $this->check($request)
            ->setProps($request)
            ->save();

        return redirect()
            ->route('transfers.edit', [$this->transfer->id])
            ->with(['ok' => true, 'msg' => 'Transfer Added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->getTransfer($id)->getData();
        return view('admin.agents.show', [
            'title' => $this->agent->data->name,
            'seo_meta' => '',
            'agent' => $this->agent,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->getTransfer($id);
        return view('admin.transfers.edit', [
            'title' => 'Edit Transfer',
            'seo_meta' => '',
            'transfer' => $this->transfer,
            'country' => $this->getCoutries(),
            'cities' => $this->getCities(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->getTransfer($id)
            ->check($request)
            ->setProps($request)
            ->save();

        return redirect()->back()->with(['ok' => true, 'msg' => 'Transfer Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transfer::destroy($id);
        return redirect()->back()->with(['ok' => true, 'msg' => 'Transfer Deleted']);
    }

    /**
     * Create a new agent or get existing one
     *
     * @param int $id
     * @return App\User
     */
    private function getTransfer($id = null)
    {
        $this->transfer = Transfer::findOrFail($id);
        return $this;
    }

    /**
     * Check form data
     *
     * @return this
     */
    private function check(Request $request)
    {
        $rules = [
            'title' => ['string', 'required'],
            'type' => ['string', 'required'],
            'country_id' => ['required'],
            'city' => [ 'required'],
        ];

        $this->validate($request, $rules);
        return $this;
    }

    /**
     * Save user data
     *
     * @param Request $request
     * @return this
     */
    private function setProps(Request $request)
    {
        //dd($this->transfer);
        $this->transfer->title = $request->title;
        $this->transfer->details = $request->details;
        $this->transfer->type = $request->type;
        $this->transfer->country_id = $request->country_id;
        $this->transfer->city_id = $request->city;
        $this->transfer->type = $request->type;
        $this->transfer->no_of_adult = $request->no_of_adult;
        $this->transfer->no_of_child = $request->no_of_child;

        $this->transfer->adult_price = $request->adult_price;
        $this->transfer->child_price = $request->child_price;

        $this->transfer->adult_allowed = $request->adult_allowed;
        $this->transfer->child_allowed = $request->child_allowed;

        if ($request->hasFile('featured_image')) {
            $this->transfer->featured_image = $request->file('featured_image')->store('featured-images', 'public');
        }

        if($request->hasfile('filename')) {
            foreach($request->file('filename') as $image) {
                $path =  $image->store('featured-images', 'public');
                $data[] = $path;
            }
            if($data) {
                $this->transfer->gallery_image =json_encode($data);
            }
        } elseif(!empty($request->filename_hidden)) {
            foreach($request->filename_hidden as $image) {
                $data[] = $image;
            }
            if($data) {
                $this->transfer->gallery_image =json_encode($data);
            }
        }

        $data_c = array();

        if(!empty($request->cancellation_date)) {
            $data_c['cancellation_date'] = $request->cancellation_date;
        }

        if(!empty($request->adult_amount))  {
            $data_c['adult_amount'] = $request->adult_amount;
        }

        if(!empty($request->child_amount)) {
            $data_c['child_amount'] = $request->child_amount;
        }

        $this->transfer->cancellation_date=json_encode($data_c);

        return $this;
    }

    /**
     * Save agent
     *
     * @return this
     */
    private function save()
    {
        $this->transfer->save();
        return $this;
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
