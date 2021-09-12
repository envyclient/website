<?php

namespace App\Http\Livewire\Admin;

use App\Models\LicenseRequest;
use App\Models\Subscription;
use App\Notifications\LicenseRequest\LicenseRequestDeniedNotification;
use App\Notifications\LicenseRequest\LicenseRequestUpdatedNotification;
use Livewire\Component;

class LicenseRequestsTable extends Component
{
    const DAYS_TO_ADD = 3;

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
                'end_date' => $user->subscription->end_date->addDays(self::DAYS_TO_ADD),
            ]);
        } else {  // subscribe the user to the free plan
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'end_date' => now()->addDays(self::DAYS_TO_ADD)
            ]);
        }

        // mark the license request as approved
        $licenseRequest->update([
            'status' => LicenseRequest::APPROVED,
            'action_reason' => 'Request approved.',
            'action_at' => now(),
        ]);

        // send email to the user
        $user->notify(new LicenseRequestUpdatedNotification(
            "Congrats $user->name,",
            'Media License Approved',
            'Your media license request has been approved and you have ' . self::DAYS_TO_ADD . ' days to use and publish a video of the client.',
            'Please visit the website to download the launcher.',
        ));

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
            'status' => LicenseRequest::DENIED,
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
