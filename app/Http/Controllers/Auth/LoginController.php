<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Rules\GoogleRecaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        if (! App::environment('local') && ! App::environment('testing')) {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
                'g-recaptcha-response' => ['required', new GoogleRecaptcha],  //this is commented for use on localhost as captcha does not work on local instance.
            ]);
        } else {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
                /*'g-recaptcha-response' => ['required', new GoogleRecaptcha],*/ //this is commented for use on localhost as captcha does not work on local instance.
            ]);
        }
    }
}
