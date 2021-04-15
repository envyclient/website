<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class ShowProfileImage extends Component
{
    protected $listeners = ['PROFILE_UPDATE' => '$refresh'];

    public function render()
    {
        return view('livewire.user.show-profile-image');
    }
}
