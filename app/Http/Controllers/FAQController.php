<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FAQController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        return view('pages.FAQ');
    }
}
