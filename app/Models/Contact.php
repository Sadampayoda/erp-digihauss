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
        'credit_limit',

        'bank_name',

        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public static array $type = [
        0 => 'Customer',
        1 => 'Vendor',
    ];
}
