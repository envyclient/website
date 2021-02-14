<?php

namespace App\Util;

class Util
{
    public static function getChannelId(string $url): string
    {
        $html = file_get_contents($url);
        preg_match("'<meta itemprop=\"channelId\" content=\"(.*?)\"'si", $html, $match);
        if ($match && $match[1])
            return $match[1];
        return '';
    }
}
