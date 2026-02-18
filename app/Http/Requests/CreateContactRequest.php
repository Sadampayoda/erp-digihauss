<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateContactRequest extends FormRequest
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
        $id = $this->route('contact');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('contacts', 'code')->ignore($id),
            ],

            'type' => [
                'required',
                'integer',
                Rule::in([0, 1]),
            ],

            'name' => 'required|string|max:255',

            'contact_person' => 'nullable|string|max:255',
            'tax_id'         => 'nullable|string|max:50',

            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',

            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'province'    => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',

            'country' => 'nullable|string|max:100',

            'credit_limit' => 'nullable|numeric|min:0',

            'bank_name' => 'nullable|string|max:255',
        ];
    }
}
