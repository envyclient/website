<?php

namespace App\Http\Livewire;

use App\Helpers\Youtube;
use App\Models\LicenseRequest;
use App\Rules\MinYouTubeSubs;
use App\Rules\ValidYouTubeLink;
use Livewire\Component;

class CreateMediaRequest extends Component
{
    public bool $open = false;
    public string $channel = '';

    public function submit()
    {
        $user = auth()->user();

        // checking if the user has an request pending
        if (LicenseRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            $this->addError('channel', 'You may only have one request at a time.');
            return;
        }

        $this->validate([
            'channel' => [
                'bail',
                'required',
                'string',
                'url',
                new ValidYouTubeLink,
                new MinYouTubeSubs
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
            'status' => 'pending',
        ]);

        session()->flash('success', 'Request submitted.');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.create-media-request');
    }
}
