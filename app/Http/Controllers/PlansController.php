<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function purchase(Request $request, Plan $item)
    {
        $user = auth()->user();
        if ($user->safePay($item)) {
            $user->pay($item);


        } else {
            return back()->with('error', 'You do not have enough credits.');
        }
    }
}
