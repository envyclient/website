<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Version;
use App\Notifications\ClientNotification;
use Illuminate\Support\Facades\Notification;
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
        $path = 'versions/' . bin2hex(openssl_random_pseudo_bytes(10));
        $this->version->storeAs($path, 'version.exe');
        $this->assets->storeAs($path, 'assets.jar');

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
