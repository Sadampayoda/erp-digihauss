<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Series extends Model
{
    protected $fillable = [
        'name',
        'code',
        'release_year',
        'is_active',
    ];

    public static function rules(?int $id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('series', 'code')->ignore($id),
            ],

            'release_year' => [
                'nullable',
                'digits:4',
                'integer',
                'min:2007', // iPhone pertama
                'max:' . now()->year,
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
