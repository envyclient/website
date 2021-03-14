<?php

namespace App\Http\Requests;

use App\Rules\ValidVersion;
use Illuminate\Foundation\Http\FormRequest;

class StoreConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:15',
            'version' => ['required', 'string', new ValidVersion],
            'data' => 'required|json',
            'public' => 'nullable|boolean',
        ];
    }
}
