<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DownloadLauncher extends Component
{
    public function render()
    {
        return view('livewire.download-launcher');
    }

    public function download()
    {
        return Storage::download('envy-launcher.exe');
    }
}
