<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowProfileImage extends Component
{
    protected $listeners = ['PROFILE_UPDATE' => '$refresh'];

    public function render()
    {
        return view('livewire.show-profile-image');
    }
}
