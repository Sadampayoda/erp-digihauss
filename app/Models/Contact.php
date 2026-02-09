<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'code',
        'type',
        'name',
        'contact_person',
        'tax_id',

        'email',
        'phone',

        'address',
        'city',
        'province',
        'postal_code',
        'country',

        'payment_terms',
        'credit_limit',
        'currency',

        'bank_name',
        'bank_account',
        'bank_account_name',

        'is_active',
        'notes',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
