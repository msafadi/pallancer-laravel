<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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

    protected $maxAttempts = 3; // 5

    protected $decayMinutes = 2; // 1

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'signout']);
    }

    public function redirectTo()
    {
        return route('frontpage');
    }

    public function username()
    {
        return 'username';
    }

    public function guard()
    {
        return Auth::guard('web');
    }

    protected function loggedOut($request)
    {
        return redirect($this->redirectTo());
    }

    public function signout()
    {
        //$request = request();
        $this->guard()->logout();
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();
        return redirect($this->redirectTo());
    }
}
