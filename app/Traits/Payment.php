<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

trait Payment
{
    function successful(): RedirectResponse
    {
        return redirect()
            ->route('home.subscription')
            ->with('success', 'Subscription successful. Please allow up to 5 minutes to process.');
    }

    function failed(): RedirectResponse
    {
        return redirect()
            ->route('home.subscription')
            ->with('error', 'Subscription failed.');
    }

    function canceled(): RedirectResponse
    {
        return redirect()
            ->route('home.subscription')
            ->with('error', 'Subscription cancelled.');
    }
}
