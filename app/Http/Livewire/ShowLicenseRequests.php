<?php

namespace App\Http\Livewire;

use App\Models\LicenseRequest;
use App\Models\Subscription;
use Livewire\Component;

class ShowLicenseRequests extends Component
{
    protected $listeners = ['DENY_REQUEST' => 'deny'];

    public string $status = 'all';

    public function approve(LicenseRequest $licenseRequest, string $type)
    {
        if ($type === 'approve') { // approve
            $this->handleSubscription($licenseRequest, 2, [
                'status' => 'approved',
                'action_reason' => 'Request approved.',
                'action_at' => now(),
            ]);
            session()->flash('success', 'Granted the user a 2 day subscription.');
        } else { // extend
            $this->handleSubscription($licenseRequest, 7, [
                'status' => 'extended',
                'action_reason' => 'License extended.',
                'action_at' => now(),
            ]);
            session()->flash('success', 'Extended the users subscription by 1 week.');
        }
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

    private function handleSubscription(LicenseRequest $licenseRequest, int $daysToAdd, array $data)
    {
        $user = $licenseRequest->user;
        if ($user->hasSubscription()) {
            $user->subscription->update([
                'end_date' => $user->subscription->end_date->addDays($daysToAdd),
            ]);
        } else {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'end_date' => now()->addDays($daysToAdd)
            ]);
        }

        $licenseRequest->update($data);
    }
}
