<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth'])->except('index');
    }

    public function index()
    {
        return view('pages.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        return view('pages.dashboard')->with([
            'user' => auth()->user(),
            'transactions' => auth()->user()->wallet->transactions,
            'plans' => Plan::all()
        ]);
    }
}
