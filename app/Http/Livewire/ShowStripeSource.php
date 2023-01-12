<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ShowStripeSource extends Component
{
    public string $stripeSourceId;

    public function mount(string $stripeSource)
    {
        $this->stripeSourceId = $stripeSource;
    }

    public function render()
    {
        $source = Cache::get($this->stripeSourceId);

        return view('livewire.show-stripe-source', compact('source'))->extends('layouts.guest');
    }
}
