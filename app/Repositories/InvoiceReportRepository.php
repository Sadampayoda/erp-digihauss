<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class InvoiceReportRepository
{
    public function sql()
    {
        $sql =
            "SELECT
                si.id AS invoice_id,
                si.transaction_number,
                si.transaction_date,
                c.code AS customer_code,
                c.name AS customer_name,
                c.contact_person,
                c.phone,
                c.email,
                c.address,
                c.city,
                c.province,
                c.country,
                si.sales,
                si.payment_method,
                si.status AS invoice_status,
                CASE
                    WHEN si.status = 0 THEN 'Draft'
                    WHEN si.status = 1 THEN 'Need Approved'
                    WHEN si.status = 2 THEN 'Approved'
                    WHEN si.status = 3 THEN 'In Progress'
                    WHEN si.status = 4 THEN 'Completed'
                    WHEN si.status = 5 THEN 'Pending'
                    WHEN si.status = 6 THEN 'Close'
                END AS invoice_status_label,

                si.sub_total AS invoice_sub_total,
                si.discount AS invoice_discount,
                si.grand_total AS invoice_grand_total,
                si.service AS invoice_service,
                si.paid_amount,
                si.remaining_amount,
                si.advance_amount,

                sii.id AS invoice_item_id,
                sii.item_code,
                sii.item_name,
                sii.quantity,
                sii.sale_price,
                sii.purchase_price,
                sii.sub_total AS item_sub_total,
                sii.service AS item_service,
                sii.margin,
                sii.notes,

                i.code AS master_item_code,
                i.name AS master_item_name,
                i.brand,
                i.model,
                i.type AS item_type,
                CASE
                    WHEN i.type = 0 THEN 'Barang Habis Pakai'
                    WHEN i.type = 1 THEN 'Barang Dagang'
                    WHEN i.type = 2 THEN 'Aset Tetap'
                END AS item_type_label,

                idt.id AS item_detail_id,
                idt.color,
                idt.internal_storage,
                idt.network,
                idt.region,
                idt.imei,
                idt.serial_number,
                idt.type AS detail_type,
                idt.has_box,
                idt.has_cable,
                idt.has_adapter,
                idt.purchase_price AS detail_purchase_price,
                idt.sale_price AS detail_sale_price,
                idt.service AS detail_service,
                idt.distributor,
                idt.purchase_date,
                idt.sale_date,
                idt.status AS item_detail_status,
                CASE
                    WHEN idt.status = 0 THEN 'Pending Receipt'
                    WHEN idt.status = 1 THEN 'In Stock'
                    WHEN idt.status = 2 THEN 'In Progress'
                    WHEN idt.status = 3 THEN 'Sold'
                    WHEN idt.status = 4 THEN 'Service'
                    WHEN idt.status = 5 THEN 'Returned'
                    WHEN idt.status = 6 THEN 'Broken'
                END AS item_detail_status_label
            FROM sales_invoices si
            LEFT JOIN contacts c
                ON c.id = si.customer
                AND c.deleted_at IS NULL
            LEFT JOIN sales_invoice_items sii
                ON sii.sales_invoice_id = si.id
                AND sii.deleted_at IS NULL
            LEFT JOIN item i
                ON i.id = sii.item_id
                AND i.deleted_at IS NULL
            LEFT JOIN item_details idt
                ON idt.id = sii.item_detail_id
                AND idt.deleted_at IS NULL
            WHERE si.deleted_at IS NULL
            ORDER BY si.transaction_date DESC, si.transaction_number DESC";

        // $data = DB::table(DB::raw("($sql) as invoice"));

        // return $data;
        return $sql;
    }


    public function viewColumn()
    {
        return [

            // ======================
            // INVOICE
            // ======================
            'invoice_id' => [
                'label' => 'Invoice ID',
            ],
            'transaction_number' => [
                'label' => 'No Transaksi',
            ],
            'transaction_date' => [
                'label' => 'Tanggal',
                'format' => 'date',
            ],
            'customer_code' => [
                'label' => 'Kode Customer',
            ],
            'customer_name' => [
                'label' => 'Customer',
            ],
            'contact_person' => [
                'label' => 'Contact Person',
            ],
            'phone' => [
                'label' => 'No HP',
            ],
            'email' => [
                'label' => 'Email',
            ],
            'address' => [
                'label' => 'Alamat',
            ],
            'city' => [
                'label' => 'Kota',
            ],
            'province' => [
                'label' => 'Provinsi',
            ],
            'country' => [
                'label' => 'Negara',
            ],
            'sales' => [
                'label' => 'Sales',
            ],
            'payment_method' => [
                'label' => 'Metode Pembayaran',
            ],
            'invoice_status' => [
                'label' => 'Status (Code)',
            ],
            'invoice_status_label' => [
                'label' => 'Status',
            ],

            'invoice_sub_total' => [
                'label' => 'Sub Total',
                'format' => 'currency',
            ],
            'invoice_discount' => [
                'label' => 'Diskon',
                'format' => 'currency',
            ],
            'invoice_grand_total' => [
                'label' => 'Grand Total',
                'format' => 'currency',
            ],
            'invoice_service' => [
                'label' => 'Service',
                'format' => 'currency',
            ],
            'paid_amount' => [
                'label' => 'Dibayar',
                'format' => 'currency',
            ],
            'remaining_amount' => [
                'label' => 'Sisa',
                'format' => 'currency',
            ],
            'advance_amount' => [
                'label' => 'DP',
                'format' => 'currency',
            ],

            // ======================
            // ITEM (DETAIL TRANSAKSI)
            // ======================
            'invoice_item_id' => [
                'label' => 'Invoice Item ID',
            ],
            'item_code' => [
                'label' => 'Kode Item',
            ],
            'item_name' => [
                'label' => 'Nama Item',
            ],
            'quantity' => [
                'label' => 'Qty',
            ],
            'sale_price' => [
                'label' => 'Harga Jual',
                'format' => 'currency',
            ],
            'purchase_price' => [
                'label' => 'Harga Beli',
                'format' => 'currency',
            ],
            'item_sub_total' => [
                'label' => 'Subtotal Item',
                'format' => 'currency',
            ],
            'item_service' => [
                'label' => 'Service Item',
                'format' => 'currency',
            ],
            'margin' => [
                'label' => 'Margin',
                'format' => 'currency',
            ],
            'notes' => [
                'label' => 'Catatan',
            ],

            // ======================
            // MASTER ITEM
            // ======================
            'master_item_code' => [
                'label' => 'Kode Master Item',
            ],
            'master_item_name' => [
                'label' => 'Nama Master Item',
            ],
            'brand' => [
                'label' => 'Brand',
            ],
            'model' => [
                'label' => 'Model',
            ],
            'item_type' => [
                'label' => 'Tipe Item (Code)',
            ],
            'item_type_label' => [
                'label' => 'Tipe Item',
            ],

            // ======================
            // ITEM DETAIL
            // ======================
            'item_detail_id' => [
                'label' => 'Detail ID',
            ],
            'color' => [
                'label' => 'Warna',
            ],
            'internal_storage' => [
                'label' => 'Storage',
            ],
            'network' => [
                'label' => 'Network',
            ],
            'region' => [
                'label' => 'Region',
            ],
            'imei' => [
                'label' => 'IMEI',
            ],
            'serial_number' => [
                'label' => 'Serial Number',
            ],
            'detail_type' => [
                'label' => 'Detail Type',
            ],
            'has_box' => [
                'label' => 'Ada Box',
            ],
            'has_cable' => [
                'label' => 'Ada Kabel',
            ],
            'has_adapter' => [
                'label' => 'Ada Adapter',
            ],
            'detail_purchase_price' => [
                'label' => 'Harga Beli Detail',
                'format' => 'currency',
            ],
            'detail_sale_price' => [
                'label' => 'Harga Jual Detail',
                'format' => 'currency',
            ],
            'detail_service' => [
                'label' => 'Service Detail',
                'format' => 'currency',
            ],
            'distributor' => [
                'label' => 'Distributor',
            ],
            'purchase_date' => [
                'label' => 'Tanggal Beli',
                'format' => 'date',
            ],
            'sale_date' => [
                'label' => 'Tanggal Jual',
                'format' => 'date',
            ],
            'item_detail_status' => [
                'label' => 'Status Detail (Code)',
            ],
            'item_detail_status_label' => [
                'label' => 'Status Detail',
            ],
        ];
    }
}

