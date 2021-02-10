<?php

namespace App\Http\Livewire;

use App\Models\LicenseRequest;
use Livewire\Component;

class ShowLicenseRequests extends Component
{
    public string $status = 'all';

    public bool $editMode = false;
    public LicenseRequest $editing;

    public function editMode(LicenseRequest $licenseRequest)
    {
        $this->editMode = true;
        $this->editing = $licenseRequest;
    }

    // approve request
    public function approve(LicenseRequest $licenseRequest)
    {
        $user = $licenseRequest->user;

        dd($user->name);
    }

    // extend the users subscription by 1 week
    public function extend(LicenseRequest $licenseRequest)
    {

    }

    public function render()
    {
        $requests = LicenseRequest::with('user')
            ->status($this->status);

        return view('livewire.show-license-requests', [
            'requests' => $requests->get()
        ])->extends('layouts.dash');
    }
}
