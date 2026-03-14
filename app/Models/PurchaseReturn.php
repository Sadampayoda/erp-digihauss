<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use App\Traits\Validate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes, Validate, CreatedUpdatedDeletedBy;
    protected $fillable = [
        'transaction_number',
        'transaction_date',

        'receipt_invoice_id',
        'vendor',
        'sales',

        'sub_total',
        'service',
        'discount',
        'grand_total',

        'paid_amount',
        'remaining_amount',

        'payment_method',
        'coa_id',

        'status',
        'description',

        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function items()
    {
        return $this->hasMany(PurchaseReturnItems::class, 'purchase_return_id', 'id');
    }

    public function vendorRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'vendor');
    }

    public function receiptInvoice()
    {
        return $this->hasOne(ReceiptInvoice::class, 'id', 'receipt_invoice_id');
    }

    public function getVendorNameAttribute()
    {
        if ($this->vendorRelation) {
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
