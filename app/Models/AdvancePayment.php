<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use App\Traits\Validate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvancePayment extends Model
{
    use HasFactory, SoftDeletes, Validate, CreatedUpdatedDeletedBy;
    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'vendor',
        'sales',
        'advance_amount',
        'grand_total',
        'payment_method',
        'status',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(AdvancePaymentItems::class, 'advance_payment_id','id');
    }

    public function vendorRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'vendor');
    }

    public function getVendorNameAttribute()
    {
        if ($this->vendorRelation()) {
            $vendor = $this->vendorRelation;
            return  $this->vendorRelation->name;
        }

        return null;
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            $item->canDelete();
        });
    }

    protected $appends = ['vendor_name'];
}
