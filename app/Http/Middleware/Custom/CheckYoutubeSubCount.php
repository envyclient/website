<?php

namespace App\Http\Middleware\Custom;

use App\Util\Util;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CheckYoutubeSubCount
{
    const MIN_SUB_COUNT = 200;

    public function handle(Request $request, Closure $next)
    {
        // TODO: check if url is valid & sub count > 200
        if ($request->missing('channel')) {
            throw ValidationException::withMessages([
                'channel' => ['Missing channel link.'],
            ]);
        }

        $url = $request->input('channel');

        // checking if the link is a valid url
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages([
                'channel' => ['Invalid channel link.'],
            ]);
        }

        // checking if the url is for youtube.com
        $host = strtolower(parse_url($url)['host']);
        if ($host !== 'youtube.com' && $host !== 'www.youtube.com') {
            throw ValidationException::withMessages([
                'channel' => ['Only YouTube links are allowed.'],
            ]);
        }

        // get the channel by visiting the url
        $id = Util::getChannelId($url);

        // checking if we were able to get the channel id
        if (empty($id)) {
            throw ValidationException::withMessages([
                'channel' => ['Invalid channel link.'],
            ]);
        }

        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
            'part' => 'statistics',
            'id' => $id,
            'key' => 'AIzaSyDHSzqM0rrkbR19PALpeu9ewZ-41A52Ryc',
        ]);

        // could not fetch sub count for channel id
        if ($response->json('pageInfo.totalResults') === 0) {
            throw ValidationException::withMessages([
                'channel' => ['Could not fetch sub count for channel.'],
            ]);
        }

        // checking if the channel meets the sub count limit
        $subs = $response->json('items.0.statistics.subscriberCount');
        if (!isset($subs) || empty($subs) || intval($subs) < self::MIN_SUB_COUNT) {
            throw ValidationException::withMessages([
                'channel' => ['Channel subscribers are less than ' . self::MIN_SUB_COUNT],
            ]);
        }

        return $next($request);
    }
}
