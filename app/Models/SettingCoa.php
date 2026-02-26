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

    public static array $module = [
        'advance-sale' => 'Uang Muka Penjualan',
        'sales-invoice' => 'Invoice Penjualan',
        'sales-return' => 'Pengembalian Barang Customer',
        'advance-payment' => 'Uang Muka Pembelian',
        'receipt-invoice' => 'Invoice Pembelian',
        'purchase-return' => 'Pengembalian Barang Vendor',
        'trade-ins' => 'Tukar Tambah',
        'service' => 'Service Iphone'
    ];

    public static array $action = [

        // ======================
        // SALES
        // ======================
        'payment'           => 'Pembayaran Customer',
        'receivable'        => 'Piutang Usaha',
        'advance'           => 'Uang Muka Penjualan',

        'revenue'           => 'Penjualan',
        'sales_discount'    => 'Diskon Penjualan',
        'sales_tax'         => 'Pajak Keluaran',

        'sales_return'      => 'Retur Penjualan',
        'sales_refund'      => 'Pengembalian Dana Customer',

        // ======================
        // PURCHASING
        // ======================
        'purchase'          => 'Pembelian',
        'payable'           => 'Hutang Usaha',
        'purchase_advance'  => 'Uang Muka Pembelian',

        'purchase_return'   => 'Retur Pembelian',

        // ======================
        // INVENTORY & COST
        // ======================
        'hpp'               => 'Harga Pokok Penjualan',
        'service'      => 'Biaya Service',

        // ======================
        // TUKAR TAMBAH
        // ======================
        'trade_in'           => 'Tukar Tambah',
        'trade_in_inventory' => 'Persediaan Tukar Tambah',

        // ======================
        // CASH & BANK
        // ======================
        'cash_in'           => 'Kas Masuk',
        'cash_out'          => 'Kas Keluar',
        'bank_in'           => 'Bank Masuk',
        'bank_out'          => 'Bank Keluar',

        // ======================
        // ADJUSTMENT
        // ======================
        'rounding'          => 'Pembulatan',
        'adjustment'        => 'Penyesuaian',
        'other'             => 'Penyesuaian Lainnya',
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method');
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
