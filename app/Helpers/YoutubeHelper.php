<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class YoutubeHelper
{
    private static function getChannelId(string $url): string
    {
        $html = file_get_contents($url);
        preg_match("'<meta itemprop=\"channelId\" content=\"(.*?)\"'si", $html, $match);
        if ($match && $match[1])
            return $match[1];
        return '';
    }

    /**
     * Get YouTube channel data.
     *
     * @param string $url the url to the channel
     * @return Response the data provided by YouTube
     * @throws Exception thrown when response has no data
     */
    public static function getChannelData(string $url): Response
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
            'id' => self::getChannelId($url),
            'key' => config('services.youtube.key'),
            'part' => 'snippet,statistics',
        ]);

        if ($response->json('pageInfo.totalResults') === 0) {
            throw new Exception('No data.');
        }

        return $response;
    }

}
