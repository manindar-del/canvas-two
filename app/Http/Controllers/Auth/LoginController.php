<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use App\Currency;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('home.book-now');
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login to the dashboard
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => 'Login',
            'seo_meta' => '',
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt([
            'user_name' => $request->user_name,
            'password' => $request->password,
            'is_active' => 1
        ])) {
            if ('admin' == Auth::user()->type) {
                return redirect()->route('admin.booking.index');
            }
            return redirect($this->redirectTo);
        }
        return redirect()->back()->with([
            'ok' => false,
            'msg' => 'Invalid Username or Password',
        ]);
    }
}
