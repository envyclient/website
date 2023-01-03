<?php

namespace App\Traits;

use Illuminate\Validation\Rules\Password;

trait ValidationRules
{
    function nameRules(): array
    {
        return ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:users'];
    }

    function emailRules(): array
    {
        return ['required', 'string', 'email', 'max:255', 'unique:users'];
    }

    function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(8)->mixedCase()->uncompromised()
        ];
    }
}
