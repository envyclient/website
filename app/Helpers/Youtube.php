<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Youtube
{
    public static function getChannelId(string $url): string
    {
        $html = file_get_contents($url);
        preg_match("'<meta itemprop=\"channelId\" content=\"(.*?)\"'si", $html, $match);
        if ($match && $match[1])
            return $match[1];
        return '';
    }

    public static function getYoutubeSubs(string $id): int
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
            'id' => $id,
            'key' => 'AIzaSyDHSzqM0rrkbR19PALpeu9ewZ-41A52Ryc',
            'part' => 'snippet,statistics',
        ]);

        // could not fetch sub count for channel id
        if ($response->json('pageInfo.totalResults') === 0) {
            return 0;
        }

        // checking if the channel meets the sub count limit
        return $response->json('items.0.statistics.subscriberCount');
    }

    public static function getChannelData(string $url): Response|null
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
            'id' => self::getChannelId($url),
            'key' => 'AIzaSyDHSzqM0rrkbR19PALpeu9ewZ-41A52Ryc',
            'part' => 'snippet,statistics',
        ]);

        if ($response->json('pageInfo.totalResults') === 0) {
            return null;
        }

        return $response;
    }

}
