<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id') !== null;

        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'current_password' => $id
                ? ['required_with:password']
                : ['nullable'],

            'password' => $id
                ? ['nullable', 'string', 'min:6', 'confirmed']
                : ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
