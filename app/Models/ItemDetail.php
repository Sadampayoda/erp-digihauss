<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemDetail extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedDeletedBy;

    protected $fillable = [
        'item_id',

        // spesifikasi
        'color',
        'internal_storage',
        'network',
        'region',

        // identitas device
        'imei',
        'serial_number',

        // tipe
        'type',

        // kelengkapan
        'has_box',
        'has_cable',
        'has_adapter',

        // harga
        'purchase_price',
        'sale_price',
        'service',

        // supplier
        'distributor',

        // tanggal
        'purchase_date',
        'sale_date',

        'status',

        // audit
        'created_by',
        'updated_by',
        'deleted_by',

        'reference_service',
    ];

    protected $casts = [
        'has_box' => 'boolean',
        'has_cable' => 'boolean',
        'has_adapter' => 'boolean',
        'purchase_date' => 'date',
        'sale_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function condition()
    {
        return $this->hasOne(ItemCondition::class, 'item_detail_id', 'id');
    }


    public function getItemCodeAttribute()
    {
        return $this->item?->code ?? null;
    }

    public function getItemNameAttribute()
    {
        return $this->item?->name ?? null;
    }

    public function journal()
    {
        return $this->hasOne(Journal::class, 'journal_number', 'reference_service');
    }

    public function advanceSale()
    {
        return $this->belongsTo(AdvanceSaleItems::class, 'id', 'item_detail_id');
    }

    public function advancePayment()
    {
        return $this->belongsTo(AdvancePaymentItems::class, 'id', 'item_detail_id');
    }

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoiceItems::class, 'id', 'item_detail_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturnItems::class, 'id', 'item_detail_id');
    }

    public function receiptInvoice()
    {
        return $this->belongsTo(ReceiptInvoiceItems::class, 'id', 'item_detail_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class,'id','item_detail_id');
    }

}
