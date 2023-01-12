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

        // storing the cape file
        $path = Storage::disk('s3_public')->putFileAs(
            'capes',
            $this->cape,
            "$user->hash.png"
        );

        $user->update([
            'cape' => Storage::disk('s3_public')->url($path),
        ]);

        $this->resetFilePond();
        $this->emitSelf('small-notify', 'Cape Uploaded!');
    }

    public function resetCape()
    {
        $user = auth()->user();

        // deleting the cape file
        Storage::disk('s3_public')->delete("capes/$user->hash.png");

        // resetting the user to the default cape
        $user->update([
            'cape' => null,
        ]);

        $this->emitSelf('small-notify', 'Cape Reset!');
    }
}
