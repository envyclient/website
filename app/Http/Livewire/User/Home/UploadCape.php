<?php

namespace App\Http\Livewire\User\Home;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCape extends Component
{
    use WithFileUploads;

    public $cape;

    protected array $rules = [
        'cape' => ['bail', 'required', 'file', 'image', 'mimes:png', 'dimensions:width=2048,height=1024', 'max:1024'],
    ];

    public function render()
    {
        $user = auth()->user();
        return view('livewire.user.home.upload-cape', compact('user'));
    }

    public function submit()
    {
        $this->validate();

        $user = auth()->user();

        // generating a hash for the cape file
        $hash = md5($user->id);

        // storing the cape file
        $path = Storage::disk('public')->putFileAs(
            'capes',
            $this->cape,
            "$hash.png"
        );

        $user->update([
            'cape' => Storage::disk('public')->url($path),
        ]);

        $this->resetFilePond();
        $this->emitSelf('small-notify', 'Cape Uploaded!');
    }

    public function resetCape()
    {
        $user = auth()->user();

        // getting the hash for the cape file
        $hash = md5($user->id);

        // deleting the cape file
        Storage::disk('public')->delete("capes/$hash.png");

        // resetting the user to the default cape
        $user->update([
            'cape' => asset('assets/capes/default.png'),
        ]);

        $this->emitSelf('small-notify', 'Cape Reset!');
    }
}
