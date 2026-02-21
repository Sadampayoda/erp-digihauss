<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingCoa extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'module',
        'action',
        'payment_method',
        'position',
        'coa_id',
        'is_active',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected static array $module = [
        'advance-sale' => 'Uang Muka Penjualan',
        'sales-invoice' => 'Invoice Penjualan',
        'advance-payment' => 'Uang Muka Pembelian',
        'receipt-invoice' => 'Invoice Pembelian',
        'trade-ins' => 'Tukar Tambah',
    ];

    protected static array $action = [
        'payment' => 'Pembayaran',
        'receivable' => 'Piutang Usaha',
        'advance' => 'Uang Muka Penjualan',

        'revenue' => 'Penjualan',
        'discount' => 'Diskon Penjualan',
        'tax' => 'Pajak Keluaran',

        'hpp' => 'Harga Pokok Penjualan',
        'service' => 'Biaya Service',

        'rounding' => 'Pembulatan',
        'other' => 'Penyesuaian Lainnya',
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class,'id','payment_method');
    }

    public function getModuleNameAttribute()
    {
        return self::$module[$this->module] ?? null;
    }

    public function getActionNameAttribute()
    {
        return self::$action[$this->action] ?? null;
    }

    public function getPaymentMethodNameAttribute()
    {
        if ($this->paymentMethod()) {
            return  $this->paymentMethod?->name;
        }

        return null;
    }

    protected $appends = [
        'module_name',
        'action_name',
        'payment_method_name'
    ];
}
