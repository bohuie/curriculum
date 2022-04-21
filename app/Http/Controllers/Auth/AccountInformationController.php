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
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request) {
        $token = $request->route()->parameter('token');
        $user = User::find(Auth::id());
        return view('auth.accountInformation')->with('user', $user)->with('token', $token);
    }

    public function update(Request $request) {
        $user = User::find(Auth::id());
        if ($request->input('name') != NULL) {
            if ($user->name != $request->input('name')) {
                $user->name = $request->input('name');
            }
        } else {
            $request->session()->flash('error','You must enter a valid name');
            return redirect('accountInformation')->with('user', $user);
        }

        if ($user->save()) {
            $request->session()->flash('success','Successfully changed name');
        } else {
            $request->session()->flash('error','Failed to change name');
        }

        return redirect('accountInformation')->with('user', $user);
    }
}