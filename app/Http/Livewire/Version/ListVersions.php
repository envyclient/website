<?php

namespace App\Http\Livewire\Version;

use App\Models\Version;
use Livewire\Component;
use Livewire\WithPagination;

class ListVersions extends Component
{
    use WithPagination;

    public $apiToken;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['VERSION_DELETED' => '$refresh'];

    public function mount(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function render()
    {
        return view('livewire.version.list-versions')->with([
            'apiToken' => auth()->user()->api_token,
            'versions' => Version::orderBy('created_at')->paginate(5),
        ]);
    }
}
