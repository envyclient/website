<?php

namespace App\Http\Livewire;

use App\Models\LicenseRequest;
use App\Models\Subscription;
use Livewire\Component;

class ShowLicenseRequests extends Component
{
    protected $listeners = ['DENY_REQUEST' => 'deny'];

    public string $status = 'all';

    public bool $editMode = false;
    public LicenseRequest $editing;

    public function editMode(LicenseRequest $licenseRequest)
    {
        $this->editMode = true;
        $this->editing = $licenseRequest;
    }

    public function approve(LicenseRequest $licenseRequest)
    {
        $user = $licenseRequest->user;
        if ($user->hasSubscription()) {
            session()->flash('error', 'This user already has a subscription.');
            return;
        }

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'end_date' => now()->addDays(2),
        ]);

        $licenseRequest->update([
            'status' => 'approved',
            'action_reason' => 'Request approved.',
            'action_at' => now(),
        ]);

        session()->flash('success', 'Granted the user a 2 day subscription.');
    }

    // extend the users subscription by 1 week
    public function extend(LicenseRequest $licenseRequest)
    {
        if ($licenseRequest->status !== 'approved') {
            session()->flash('error', 'This request has not been approved.');
            return;
        }

        $user = $licenseRequest->user;
        if ($user->hasSubscription()) {
            $user->subscription->update([
                'end_date' => $user->subscription->end_date->addWeek(),
            ]);
        } else {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'end_date' => now()->addWeek()
            ]);
        }

        $licenseRequest->update([
            'status' => 'extended',
            'action_reason' => 'License extended.',
            'action_at' => now(),
        ]);

        session()->flash('success', 'Extended the users subscription by 1 week.');
    }

    public function deny($payload)
    {
        if ($payload['message'] === null) {
            return;
        }

        LicenseRequest::where('id', $payload['id'])->update([
            'status' => 'denied',
            'action_reason' => $payload['message'],
            'action_at' => now(),
        ]);

        session()->flash('success', 'Request denied.');
    }

    public function render()
    {
        $requests = LicenseRequest::with('user.subscription.plan')
            ->status($this->status);

        return view('livewire.show-license-requests', [
            'requests' => $requests->get()
        ])->extends('layouts.dash');
    }
}
