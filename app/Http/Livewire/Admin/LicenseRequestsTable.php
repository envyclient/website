<?php

namespace App\Http\Livewire\Admin;

use App\Models\LicenseRequest;
use App\Models\Subscription;
use App\Notifications\LicenseRequest\LicenseRequestApprovedNotification;
use App\Notifications\LicenseRequest\LicenseRequestDeniedNotification;
use Livewire\Component;

class LicenseRequestsTable extends Component
{
    protected $listeners = ['DENY_REQUEST' => 'deny'];

    public string $status = 'all';

    public function render()
    {
        $requests = LicenseRequest::with('user.subscription.plan')
            ->status($this->status)
            ->get();

        return view('livewire.admin.license-requests-table', compact('requests'))
            ->extends('layouts.dash');
    }

    public function approve(LicenseRequest $licenseRequest)
    {
        $user = $licenseRequest->user;
        if ($user->hasSubscription()) { // extend the users current subscription
            $user->subscription->update([
                'end_date' => $user->subscription->end_date->addDays(LicenseRequest::DAYS_TO_ADD),
            ]);
        } else {  // subscribe the user to the free plan
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'status' => \App\Enums\Subscription::ACTIVE,
                'end_date' => now()->addDays(LicenseRequest::DAYS_TO_ADD)
            ]);
        }

        // mark the license request as approved
        $licenseRequest->update([
            'status' => \App\Enums\LicenseRequest::APPROVED,
            'action_reason' => 'Request approved.',
            'action_at' => now(),
        ]);

        // send email to the user
        $user->notify(new LicenseRequestApprovedNotification());

        session()->flash('success', 'Approved the license request.');
    }

    public function deny($payload)
    {
        // user clicked cancel on the prompt
        if ($payload['message'] === null) {
            return;
        }

        // mark the license request as denied
        $licenseRequest = LicenseRequest::findOrFail($payload['id']);
        $licenseRequest->update([
            'status' => \App\Enums\LicenseRequest::DENIED,
            'action_reason' => $payload['message'],
            'action_at' => now(),
        ]);

        // send email to the user
        $licenseRequest->user->notify(
            new LicenseRequestDeniedNotification($payload['message'])
        );

        session()->flash('success', 'Request denied.');
    }
}
