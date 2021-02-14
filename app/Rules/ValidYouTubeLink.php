<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidYouTubeLink implements Rule
{
    public function passes($attribute, $value): bool
    {
        $host = strtolower(parse_url($value)['host']);
        return !($host !== 'youtube.com' && $host !== 'www.youtube.com');
    }

    public function message(): string
    {
        return ':attribute is not a valid youtube link.';
    }
}
