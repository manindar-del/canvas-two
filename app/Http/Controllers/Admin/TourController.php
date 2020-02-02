<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Tour;
use App\City;
use App\Country;

use Illuminate\Validation\Rule;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class TourController extends \App\Http\Controllers\Controller
{
    protected $tour;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tours = Tour::get();
        return view('admin.tours.index', [
            'title' => 'All Tours',
            'seo_meta' => '',
            'tours' => $tours,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tours.create', [
            'title' => 'Add New Tour',
            'seo_meta' => '',
            'country' => Country::orderBy('name', 'asc')->where('code', 'TH')->get(),
            'cities' => City::with(['country'])->where('country_code', 'TH')->orderBy('name', 'asc')->get(),
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
        $this->tour = new Tour;
        $this->check($request)
            ->setProps($request)
            ->save();

        return redirect()
            ->route('tours.edit', [$this->tour->id])
            ->with(['ok' => true, 'msg' => 'Tour Added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->getTour($id)->getData();
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
       // $this->tour = Tour::findOrFail($id);
       //  dd($this->tour->cancellation_date);
      $this->getTour($id);
        return view('admin.tours.edit', [
            'title' => 'Edit Tour',
            'seo_meta' => '',
            'tour' => $this->tour,
            'country' => Country::orderBy('name', 'asc')->where('code', 'TH')->get(),
            'cities' => City::with(['country'])->where('country_code', 'TH')->orderBy('name', 'asc')->get(),
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
        $this->getTour($id)
            ->check($request)
            ->setProps($request)
            ->save();

        return redirect()->back()->with(['ok' => true, 'msg' => 'Tour Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Tour::destroy($id);
        return redirect()->back()->with(['ok' => true, 'msg' => 'Tour Deleted']);
    }

    /**
     * Remove all agents from the database
     *
     * @return void
     */
    public function delete()
    {
        User::latest()->delete();
        return redirect()->back()->with(['ok' => true, 'msg' => 'All Agent Data Deleted']);
    }

    /**
     * Create a new agent or get existing one
     *
     * @param int $id
     * @return App\User
     */
    private function getTour($id = null)
    {
        $this->tour = Tour::findOrFail($id);
        return $this;
    }



    /**
     * Check form data
     *
     * @return this
     */
    private function check(Request $request)
    {
        //$id = $this->tours ? $this->tours->id : null;
        $rules = [
            'title' => ['string', 'required'],
            'type' => ['string', 'required'],
            'country_id' => ['required'],
            'city' => [ 'required'],
            'pick_up_time' => ['required'],
            'start_time' => ['required'],
            'end_time' => [ 'required'],
            //'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        //dd($this->tour);
        $this->tour->title = $request->title;
        $this->tour->type = $request->type;
        $this->tour->address = $request->address;
        $this->tour->phone = $request->phone;
        $this->tour->country_id = $request->country_id;
        $this->tour->city_id = $request->city;
        $this->tour->type = $request->type;
        $this->tour->pick_up_time = $request->pick_up_time;
        $this->tour->start_time = $request->start_time;
        $this->tour->end_time = $request->end_time;
        $this->tour->details = $request->details;
        $this->tour->no_of_adult = $request->no_of_adult;
        $this->tour->no_of_child = $request->no_of_child;
        $this->tour->no_of_infant = $request->no_of_infant;

        $this->tour->adult_price = $request->adult_price;
        $this->tour->child_price = $request->child_price;
        $this->tour->infant_price = $request->infant_price;

        $this->tour->adult_allowed = $request->adult_allowed;
        $this->tour->child_allowed = $request->child_allowed;
        $this->tour->infant_allowed = $request->infant_allowed;
        if ($request->hasFile('featured_image')) {
              $this->tour->featured_image = $request->file('featured_image')->store('featured-images', 'public');
        }
        if($request->hasfile('filename'))
         {

            foreach($request->file('filename') as $image)
            {
                $path =  $image->store('featured-images', 'public');
                $data[] = $path;
            }
            if($data){
                $this->tour->gallery_image =json_encode($data);
            }
         }elseif(!empty($request->filename_hidden)){

            foreach($request->filename_hidden as $image)
            {
               // $path =  $image->store('featured-images', 'public');
                $data[] = $image;
            }

            if($data){
                $this->tour->gallery_image =json_encode($data);
            }

         }


        $data_c = array();
       if(!empty($request->cancellation_date)) $data_c['cancellation_date'] = $request->cancellation_date;
       if(!empty($request->adult_amount)) $data_c['adult_amount'] = $request->adult_amount;
       if(!empty($request->child_amount)) $data_c['child_amount'] = $request->child_amount;
       if(!empty($request->infant_amount)) $data_c['infant_amount'] = $request->infant_amount;
        // dd(json_encode($data_c));

         $this->tour->cancellation_date=json_encode($data_c);

        // asset('storage/' . $tour->featured_image);

        return $this;
    }

    /**
     * Save agent
     *
     * @return this
     */
    private function save()
    {
        // dd($this->agent);
        $this->tour->save();
        return $this;
    }


    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
