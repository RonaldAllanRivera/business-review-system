<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('businesses', 'slug')->ignore($this->business?->id),
            ],
            'description' => ['sometimes', 'nullable', 'string'],
            'rating' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:5'],
        ];
    }
}
