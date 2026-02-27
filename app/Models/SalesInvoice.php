<?php

namespace App\Models;

use App\Traits\Validate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    use HasFactory, SoftDeletes, Validate;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'advance_sale_id',
        'customer',
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
        return $this->hasMany(SalesInvoiceItems::class, 'sales_invoice_id', 'id');
    }

    public function customerRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'customer');
    }

    public function AdvanceSale()
    {
        return $this->hasOne(AdvanceSale::class, 'id', 'advance_sale_id');
    }

    public function getCustomerNameAttribute()
    {
        if ($this->customerRelation()) {
            $customer = $this->customerRelation;
            return  $this->customerRelation->name;
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



    protected $appends = ['customer_name', 'summary_paid'];
}
