<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use App\Traits\Validate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptInvoice extends Model
{
    use HasFactory, SoftDeletes, Validate, CreatedUpdatedDeletedBy;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'advance_payment_id',
        'vendor',
        'sales',
        'payment_method',
        'status',
        'description',
        'sub_total',
        'discount',
        'grand_total',
        'service',
        'paid_amount',
        'remaining_amount',
        'advance_amount',
        'coa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(ReceiptInvoiceItems::class, 'receipt_invoice_id', 'id');
    }

    public function vendorRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'vendor');
    }

    public function AdvancePayment()
    {
        return $this->hasOne(AdvancePayment::class, 'id', 'advance_payment_id');
    }

    public function getVendorNameAttribute()
    {
        if ($this->vendorRelation()) {
            $vendor = $this->vendorRelation;
            return  $this->vendorRelation->name;
    }

        return null;
    }

    public function getSummaryPaidAttribute()
    {
        return $this->advance_amount + $this->paid_amount;
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            $item->canDelete();
        });
    }



    protected $appends = ['vendor_name', 'summary_paid'];
}
