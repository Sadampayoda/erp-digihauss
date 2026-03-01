<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePermissionRequest extends FormRequest
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

        $permissionId = $this->route('permission');
        // sesuaikan dengan route model binding kamu
        // contoh route: permissions/{permission}

        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],

            'permissions' => [
                'required',
                'array',
                'min:1',
            ],

            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User wajib dipilih.',
            'user_id.exists' => 'User tidak valid.',
            'user_id.unique' => 'User ini sudah memiliki hak akses.',

            'permissions.required' => 'Minimal satu hak akses harus dipilih.',
            'permissions.array' => 'Format hak akses tidak valid.',
            'permissions.min' => 'Minimal satu hak akses harus dipilih.',
            'permissions.*.exists' => 'Hak akses tidak valid.',
        ];
    }
}
