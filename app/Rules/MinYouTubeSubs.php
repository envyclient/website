<?php

namespace App\Rules;

use App\Helpers\Youtube;
use Illuminate\Contracts\Validation\Rule;

class MinYouTubeSubs implements Rule
{
    public function passes($attribute, $value): bool
    {
        // get the channel by visiting the url
        $id = Youtube::getChannelId($value);

        // checking if we were able to get the channel id
        if (empty($id)) {
            return false;
        }

        // getting the amount of subs for the channel
        $subs = Youtube::getYoutubeSubs($id);

        // checking the sub count is less than 200
        if (intval($subs) < 200) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'Channel does not meet subscriber count.';
    }
}
