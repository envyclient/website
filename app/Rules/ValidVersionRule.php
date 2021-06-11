<?php

namespace App\Rules;

use App\Models\Version;
use Illuminate\Contracts\Validation\Rule;

class ValidVersionRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Version::where('name', "Envy $value")->exists();
    }

    public function message(): string
    {
        return 'Version is not valid.';
    }
}
