<?php

namespace App\Traits;

trait ValidationRules
{
    public function nameRules(): array
    {
        return ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:users'];
    }

    public function emailRules(): array
    {
        return ['required', 'string', 'email', 'max:255', 'unique:users'];
    }

    public function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'max:255',
            'same:passwordConfirmation',
        ];
    }
}
