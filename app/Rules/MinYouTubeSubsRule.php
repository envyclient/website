<?php

namespace App\Rules;

use App\Helpers\YoutubeHelper;
use App\Models\LicenseRequest;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class MinYouTubeSubsRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        // get channel data
        try {
            $response = YoutubeHelper::getChannelData($value);
        } catch (Exception) {
            return false;
        }

        // get subscriber count
        $sub = intval(
            $response->json('items.0.statistics.subscriberCount')
        );

        // checking the sub count meets the required count
        if ($sub < LicenseRequest::SUBSCRIBER_REQUIREMENT) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'Channel does not meet subscriber count.';
    }
}
