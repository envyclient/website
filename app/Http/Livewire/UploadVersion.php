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
    public $version;
    public $assets;

    protected $rules = [
        'name' => 'required|string|max:30|unique:versions',
        'changelog' => 'required|string',
        'beta' => 'nullable',
        'version' => 'required|file|max:40000',
        'assets' => 'required|file|max:10000',
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
        $this->version->storeAs($path, 'version.exe', 's3');
        $this->assets->storeAs($path, 'assets.jar', 's3');

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
        $this->version = null;
        $this->assets = null;
    }
}
