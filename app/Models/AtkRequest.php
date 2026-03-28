<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtkRequest extends Model
{
    use HasFactory, CreatedUpdatedDeletedBy, SoftDeletes;
    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'employee_id',
        'requested_fulfillment_date',
        'purpose',
        'payment_method',
        'grand_total',
        'paid_amount',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(AtkRequestItems::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function getEmployedNameAttribute()
    {
        return $this->employee?->name ?? '';
    }

    public function getAppovedNameAttribute()
    {
        return $this->approvedBy?->name ?? '';
    }


}
