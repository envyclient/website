<?php

namespace App\Rules;

use App\Util\Util;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class CountSubs implements Rule
{
    public function passes($attribute, $value): bool
    {
        // checking if the url is for youtube.com
        $host = strtolower(parse_url($value)['host']);
        if ($host !== 'youtube.com' && $host !== 'www.youtube.com') {
            return false;
        }

        // get the channel by visiting the url
        $id = Util::getChannelId($value);

        // checking if we were able to get the channel id
        if (empty($id)) {
            return false;
        }

        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
            'part' => 'statistics',
            'id' => $id,
            'key' => 'AIzaSyDHSzqM0rrkbR19PALpeu9ewZ-41A52Ryc',
        ]);

        // could not fetch sub count for channel id
        if ($response->json('pageInfo.totalResults') === 0) {
            return false;
        }

        // checking if the channel meets the sub count limit
        $subs = $response->json('items.0.statistics.subscriberCount');
        if (!isset($subs) || empty($subs) || intval($subs) < 200) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'Channel does not meet subscriber count.';
    }
}
