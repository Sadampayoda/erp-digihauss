<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Contact;
use App\Models\AdvanceSaleItems;

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
        return $this->hasMany(AdvanceSaleItems::class, 'advance_sale_id','id');
    }

    public function customerRelation()
    {
        return $this->hasOne(Contact::class, 'id', 'customer');
    }

    public function getCustomerNameAttribute()
    {
        if ($this->customerRelation()) {
            $customer = $this->customerRelation;
            return  $this->customerRelation->name;
        }

        return null;
    }

    protected $appends = ['customer_name'];
}
