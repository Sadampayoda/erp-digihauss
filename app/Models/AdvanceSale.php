<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvanceSale extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'advance_sales';

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'customer',
        'sales',
        'advance_amount',
        'remaining_amount',
        'payment_method',
        'status',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(AdvanceSaleItems::class,'id');
    }
}
