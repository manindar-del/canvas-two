<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mail;

use App\User;
use App\Country;
use App\City;
use App\Mail\Activated;

use Illuminate\Validation\Rule;
use GuzzleHttp\Client;



class AgentController extends \App\Http\Controllers\Controller
{
    protected $agent;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = User::where('type', 'agent')->latest()->get();
        return view('admin.agents.index', [
            'title' => 'All agents',
            'seo_meta' => '',
            'agents' => $agents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.agents.create', [
            'title' => 'Add New Agent',
            'seo_meta' => '',
            'countries' => Country::all(),
            'cities' => City::all(),
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
        $this->check($request)
            ->getAgent()
            ->getData()
            ->setProps($request)
            ->save();

        return redirect()
            ->route('agents.edit', [$this->agent->id])
            ->with(['ok' => true, 'msg' => 'Agent Added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->getAgent($id)->getData();
        return view('admin.agents.show', [
            'title' => $this->agent->data->name,
            'seo_meta' => '',
            'agent' => $this->agent,
            'admin' => $this->admin,
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
        $this->getAgent($id)->getData();
        return view('admin.agents.edit', [
            'title' => 'Edit Agent',
            'seo_meta' => '',
            'agent' => $this->agent,
            'countries' => Country::all(),
            'cities' => City::all(),
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
        $this->getAgent($id);
        $was_active = $this->agent->is_active;


        $this->getData()
            ->check($request)
            ->setProps($request)
            ->save();

        if (!$was_active && $this->agent->is_active) {
            $admin = User::find(1);

            Mail::to($this->agent)->send(new Activated($this->agent));

             $test = Mail::to($admin)->send(new Activated($this->agent));
             dd($test);

        }

        return redirect()->back()->with(['ok' => true, 'msg' => 'Agent Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->getAgent($id);
        $this->agent->active = null;
        User::destroy($id);
        return redirect()->back()->with(['ok' => true, 'msg' => 'Agent Deleted']);
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
    private function getAgent($id = null)
    {
        $this->agent = $id ? User::findOrFail($id) : new User;
        return $this;
    }

    /**
     * Decode agent data
     *
     * @return this
     */
    public function getData()
    {
        $this->agent->data = json_decode($this->agent->data);
        if (!$this->agent->data) {
            $this->agent->data = new \stdClass;
        }
        return $this;
    }

    /**
     * Check form data
     *
     * @return this
     */
    private function check(Request $request)
    {
        $id = $this->agent ? $this->agent->id : null;
        $rules = [
            // 'agent_code' => ['string', 'required', Rule::unique('users')->ignore($id)],
            'user_name' => ['string', 'required', Rule::unique('users')->ignore($id)],
            'email' => ['string', 'required', Rule::unique('users')->ignore($id)],
            // 'currency' => ['string', 'required', Rule::unique('users')->ignore($id)],
        ];

        if (!$id) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        }

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
        $agent = $this->agent;

        // $agent->agent_code = $request->agent_code;
        $agent->user_name = $request->user_name;
        $agent->email = $request->email;
        $agent->is_active = !empty($request->is_active) ? 1 : null;
        $agent->type = 'agent';
        $agent->currency = 'INR';
        $agent->data = null;
        $agent->api_token = hash('sha256', $request->email);

        $agent->hotel_hike = $request->hotel_hike;
        $agent->tour_hike = $request->tour_hike;
        $agent->transfer_hike = $request->transfer_hike;

        $agent->name = $request->name;
        $agent->company_name = $request->company_name;
        $agent->company_address = $request->company_address;
        $agent->country = $request->country;
        $agent->city = $request->city;
        $agent->phone = $request->phone;
        $agent->fax = $request->fax;
        $agent->website = $request->website;
        $agent->salutation = $request->salutation;
        $agent->position = $request->position;

        if (!empty($request->password)) {
            $agent->password = bcrypt($request->password);
        }

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
        $this->agent->save();
        return $this;
    }

    /**
     * Add wallet balance
     *
     * @return \Illuminate\Http\Response
     */
    public function addWalletBalance()
    {
        return view('admin.agents.add-wallet-balance', [
            'title' => 'Add Wallet Balance',
            'seo_meta' => '',
        ]);
    }

    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
