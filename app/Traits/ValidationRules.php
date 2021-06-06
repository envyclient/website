<?php

namespace App\Traits;

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
        return ['required', 'string', 'min:8', 'same:passwordConfirmation'];
    }

}
