<?php

namespace App\Http\Livewire\User;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCape extends Component
{
    use WithFileUploads;

    public $cape;

    protected array $rules = [
        'cape' => [
            'bail',
            'required',
            'file',
            'image',
            'mimes:png',
            'dimensions:width=2048,height=1024',
            'max:1024',
        ],
    ];

    public function render()
    {
        return view('livewire.user.upload-cape')
            ->with('user', auth()->user());
    }

    public function submit()
    {
        $this->validate();

        $user = auth()->user();

        $fileName = md5($user->email) . '.png';
        $path = Storage::disk('public')->putFileAs('capes', $this->cape, $fileName);

        $user->update([
            'cape' => Storage::disk('public')->url($path),
        ]);

        $this->resetFilePond();
        $this->emitSelf('small-notify', 'Cape Uploaded!');
    }

    public function resetCape()
    {
        $user = auth()->user();

        // deleting the actual cape
        $hash = md5($user->email);
        Storage::disk('public')->delete("capes/$hash.png");

        $user->update([
            'cape' => asset('assets/capes/default.png'),
        ]);

        $this->emitSelf('small-notify', 'Cape Reset!');
    }
}
