<?php

namespace App\Http\Livewire\Version;

use App\Models\Version;
use Livewire\Component;
use Livewire\WithPagination;

class ListVersions extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['UPDATE_VERSIONS' => '$refresh'];

    public function render()
    {
        return view('livewire.version.list-versions', [
            'versions' => Version::orderBy('created_at')->paginate(5),
        ]);
    }
}
