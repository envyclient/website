<?php

namespace App\Http\Livewire\User;

use App\Helpers\Youtube;
use App\Models\LicenseRequest;
use App\Rules\MinYouTubeSubsRule;
use App\Rules\ValidYouTubeLinkRule;
use Livewire\Component;

class MediaRequests extends Component
{
    public bool $open = false;
    public string $channel = '';

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

        // checking if the user has an request pending
        if (LicenseRequest::where('user_id', $user->id)->where('status', LicenseRequest::PENDING)->exists()) {
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
        $youtubeData = Youtube::getChannelData($this->channel);

        // show error
        if ($youtubeData === null) {
            $this->addError('channel', 'Unable to fetch data from YouTube.');
            return;
        }

        // create license request
        LicenseRequest::create([
            'user_id' => $user->id,
            'channel' => $this->channel,
            'channel_name' => $youtubeData->json('items.0.snippet.title'),
            'channel_image' => $youtubeData->json('items.0.snippet.thumbnails.default.url'),
            'status' => LicenseRequest::PENDING,
        ]);

        session()->flash('success', 'Request submitted.');

        return redirect()->route('home');
    }

    public function render()
    {
        $licenseRequests = auth()->user()->licenseRequests;
        return view('livewire.user.media-requests', compact('licenseRequests'));
    }
}
