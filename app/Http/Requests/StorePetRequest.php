<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'photo_urls'    => ['nullable', 'string'],
            'tags'          => ['nullable', 'string'],
            'status'        => ['required', 'in:available,pending,sold'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nazwa zwierzaka jest wymagana.',
            'name.max'        => 'Nazwa może mieć maksymalnie 255 znaków.',
            'status.required' => 'Status jest wymagany.',
            'status.in'       => 'Nieprawidłowy status (dozwolone: available, pending, sold).',
        ];
    }
}
