<?php

namespace App\Http\Livewire;

use App\Models\StripeSource;
use Livewire\Component;

class ShowStripeSource extends Component
{
    public $source;

    public function mount($id)
    {
        $this->source = StripeSource::with('plan')
            ->where('user_id', auth()->id())
            ->where('source_id', $id)
            ->firstOrFail();
    }

    public function render()
    {
        $events = $this->source
            ->events()
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.show-stripe-source', compact('events'))
            ->extends('layouts.guest');
    }
}
