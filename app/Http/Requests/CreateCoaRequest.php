<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCoaRequest extends FormRequest
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
        $coaId = $this->route('coa');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in(['asset', 'liability', 'equity', 'income', 'expense']),
            ],

            'parent_id' => [
                'nullable',
                Rule::exists('coas', 'id')->where(function ($q) {
                    $q->where('is_active', true);
                }),
            ],

            'is_postable' => [
                'required',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama akun wajib diisi.',
            'type.required' => 'Tipe akun wajib dipilih.',
            'type.in' => 'Tipe akun tidak valid.',
            'parent_id.exists' => 'Parent akun tidak valid.',
            'is_postable.required' => 'Tentukan apakah akun bisa dipakai transaksi.',
        ];
    }
}
