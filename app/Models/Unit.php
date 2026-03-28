<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Unit extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedDeletedBy;

    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function rules(?int $id = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units', 'code')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
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
