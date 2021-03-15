<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Version;
use App\Notifications\ClientNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $changelog = '';
    public bool $beta = false;
    public $files = [];

    protected array $rules = [
        'name' => 'bail|required|string|max:30|unique:versions',
        'changelog' => 'required|string',
        'beta' => 'nullable',
        'files.*' => 'bail|required|file|max:25000|min:2',
    ];

    public function render()
    {
        return view('livewire.upload-version');
    }

    public function submit()
    {
        $this->validate();

        // store version & assets
        $path = 'versions/' . Str::uuid();
        foreach ($this->files as $file) {
            if ($file->extension() === 'jar') {
                $file->storeAs($path, 'assets.jar');
            } else {
                $file->storeAs($path, 'version.exe');
            }
        }

        Version::create([
            'name' => $this->name,
            'beta' => $this->beta,
            'version' => "$path/version.exe",
            'assets' => "$path/assets.jar",
            'changelog' => $this->changelog,
        ]);

        // send the notification to all users
        Notification::send(
            User::all(),
            new ClientNotification('info', "$this->name has been released."),
        );

        session()->flash('message', 'Version upload.');
        $this->resetInputFields();

        $this->emit('UPDATE_VERSIONS');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->changelog = '';
        $this->beta = false;
        $this->files = [];
        $this->resetFilePond();
    }
}
