<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Config implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \App\Config::where('id', $value)->exists() && \App\Config::where('id', $value)->public == true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided :attribute is not valid.';
    }
}
