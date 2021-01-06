<?php

namespace App\Helpers;

class Strng
{
    public static function clean(string $value): string
    {
        return str_replace(
            ' ',
            '_',
            Strng::stripEmoji($value)
        );
    }

    public static function stripEmoji(string $value): string
    {
        return preg_replace(
            "~[^a-zA-Z0-9_ !@#$%^&*();\\\/|<>\"'+.,:?=-]~",
            "",
            $value
        );
    }


}
