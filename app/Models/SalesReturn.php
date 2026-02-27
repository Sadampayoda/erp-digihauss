<?php

namespace App\Models;

use App\Traits\Validate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes, Validate;
    protected $fillable = [
        'transaction_number',
        'transaction_date',

        'sales_invoice_id',
        'customer',
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
        return $this->hasMany(SalesReturnItems::class, 'sales_return_id','id');
    }

    public function customerRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'customer');
    }

    public function salesInvoice()
    {
        return $this->hasOne(SalesInvoice::class, 'id', 'sales_invoice_id');
    }

    public function getCustomerNameAttribute()
    {
        if ($this->customerRelation()) {
            $customer = $this->customerRelation;
            return  $this->customerRelation->name;
        }

        return null;
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            $item->canDelete();
        });
    }

    protected $appends = ['customer_name'];
}
