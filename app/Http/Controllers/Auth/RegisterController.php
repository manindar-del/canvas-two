<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Mail;
use App\User;
use App\Mail\Welcome;
use App\Mail\NewUser;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/logout';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

/**
 * Create a new user instance after a valid registration.
 *
 * @param  array  $data
 * @return \App\User
 */



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_name' => 'required|string|max:128|unique:users',
            'email' => 'required|string|email|max:160|unique:users|confirmed',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    protected function create(array $data)
    {
        $user = User::create([
            'user_name' => $data['user_name'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'api_token' => bcrypt($data['email']),
            'company_name' => $data['company_name'],
            'company_address' => $data['company_address'],
            'country' => $data['country'],
            'city' => $data['city'],
            'phone' => $data['phone'],
            'fax' => $data['fax'],
            'website' => $data['website'],
            'salutation' => $data['salutation'],
            'position' => $data['position'],
            'type' => 'agent',
        ]);
        $admin = User::find(1)->first();
        //$admin = User::where('admin', 1)
        //if ($admin) {
            //$admin->notify(new NewUser($user));
        Mail::to($user)->send(new Welcome($user));
        Mail::to($admin)->send(new NewUser($user));
        return $user;
    }
}



