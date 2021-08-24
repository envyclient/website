<?php

namespace App\Rules;

use App\Helpers\Youtube;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class MinYouTubeSubsRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        // get channel data
        try {
            $response = Youtube::getChannelData($value);
        } catch (Exception) {
            return false;
        }

        // get subscriber count
        $sub = intval($response->json('items.0.statistics.subscriberCount'));

        // checking the sub count is less than 200
        if ($sub < 200) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'Channel does not meet subscriber count.';
    }
}
