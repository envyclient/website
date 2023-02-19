<?php

namespace App\Http\Livewire\User\Home;

use App\Helpers\YoutubeHelper;
use App\Models\LicenseRequest;
use App\Rules\MinYouTubeSubsRule;
use App\Rules\ValidYouTubeLinkRule;
use Exception;
use Livewire\Component;

class LicenseRequests extends Component
{
    public bool $open = false;

    public string $channel = '';

    public function render()
    {
        $user = auth()->user();
        $licenseRequests = $user->licenseRequests;

        return view('livewire.user.home.license-requests', compact('user', 'licenseRequests'));
    }

    public function submit()
    {
        // do basic validation
        $this->validate([
            'channel' => [
                'bail',
                'required',
                'string',
                'url',
                new ValidYouTubeLinkRule,
            ],
        ]);

        $user = auth()->user();

        // checking if the user has a request pending
        if (
            LicenseRequest::where('user_id', $user->id)
                ->where('status', \App\Enums\LicenseRequest::PENDING->value)
                ->exists()
        ) {
            $this->addError('channel', 'You may only have one request at a time.');

            return;
        }

        // do sub count validation
        $this->validate([
            'channel' => [
                new MinYouTubeSubsRule,
            ],
        ]);

        // getting the channel data
        try {
            $youtubeData = YoutubeHelper::getChannelData($this->channel);
        } catch (Exception) {
            $this->addError('channel', 'Unable to fetch data from YouTube.');

            return;
        }

        // create license request
        LicenseRequest::create([
            'user_id' => $user->id,
            'channel' => $this->channel,
            'channel_name' => $youtubeData->json('items.0.snippet.title'),
            'channel_image' => $youtubeData->json('items.0.snippet.thumbnails.default.url'),
            'status' => \App\Enums\LicenseRequest::PENDING->value,
        ]);

        session()->flash('success', 'License request submitted.');

        return redirect()->route('home');
    }
}
