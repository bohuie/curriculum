<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Block\Element\IndentedCode;
use Symfony\Component\Console\Input\Input;

class AccountInformationController extends Controller
{
    /**
     * Where to redirect users when the intended url fails.
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
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $user = User::find(Auth::id());
        return view('auth.accountInformation')->with('user', $user);
    }

    public function update(Request $request) {
        $user = User::find(Auth::id());
        if ($request->input('name') != NULL) {
            if ($user->name != $request->input('name')) {
                $user->name = $request->input('name');
            }
        } else {
            
        }

        if ($user->save()) {

        } else {

        }

        return redirect('accountInformation')->with('user', $user);
    }
}