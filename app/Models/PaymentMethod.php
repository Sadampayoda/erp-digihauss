<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PaymentMethod extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];


    public static function rules(?int $id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('payment_methods', 'code')->ignore($id),
            ],
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    public static function messages(): array
    {
        return [
            'code.required' => 'Code wajib diisi.',
            'code.string'   => 'Code harus berupa teks.',
            'code.max'      => 'Code maksimal 20 karakter.',
            'code.unique'   => 'Code sudah digunakan.',

            'name.required' => 'Nama wajib diisi.',
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama maksimal 100 karakter.',
        ];
    }
}
