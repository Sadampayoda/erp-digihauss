<?php

namespace Database\Seeders;

use App\Models\Coa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coas = [

            // LEVEL 1
            ['code' => '1.0.00', 'name' => 'ASET', 'type' => 'asset', 'level' => 1],
            ['code' => '2.0.00', 'name' => 'KEWAJIBAN', 'type' => 'liability', 'level' => 1],
            ['code' => '3.0.00', 'name' => 'MODAL', 'type' => 'equity', 'level' => 1],
            ['code' => '4.0.00', 'name' => 'PENDAPATAN', 'type' => 'income', 'level' => 1],
            ['code' => '5.0.00', 'name' => 'BEBAN', 'type' => 'expense', 'level' => 1],


            // ======================
            // LEVEL 2 - ASET
            // ======================
            ['code' => '1.1.00', 'name' => 'Kas', 'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],
            ['code' => '1.2.00', 'name' => 'Bank', 'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],
            ['code' => '1.3.00', 'name' => 'E-Wallet', 'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],


            // LEVEL 3 - KAS
            ['code' => '1.1.01', 'name' => 'Kas Office', 'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],
            ['code' => '1.1.02', 'name' => 'Kas Kecil', 'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],
            ['code' => '1.1.03', 'name' => 'Kas Lainnya', 'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],
            ['code' => '1.1.04', 'name' => 'Kas Operasional', 'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],


            // LEVEL 3 - BANK
            ['code' => '1.2.01', 'name' => 'Bank BCA', 'type' => 'asset', 'level' => 3, 'parent' => '1.2.00'],
            ['code' => '1.2.02', 'name' => 'Bank Mandiri', 'type' => 'asset', 'level' => 3, 'parent' => '1.2.00'],


            // LEVEL 3 - E WALLET
            ['code' => '1.3.01', 'name' => 'Dana', 'type' => 'asset', 'level' => 3, 'parent' => '1.3.00'],
            ['code' => '1.3.02', 'name' => 'ShopeePay', 'type' => 'asset', 'level' => 3, 'parent' => '1.3.00'],
            ['code' => '1.3.03', 'name' => 'GoPay', 'type' => 'asset', 'level' => 3, 'parent' => '1.3.00'],


            // ======================
            // LEVEL 2 - KEWAJIBAN
            // ======================
            ['code' => '2.1.00', 'name' => 'Hutang Usaha', 'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],
            ['code' => '2.2.00', 'name' => 'Hutang Bank', 'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],
            ['code' => '2.3.00', 'name' => 'Hutang Staff', 'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],


            // LEVEL 3 - HUTANG USAHA
            ['code' => '2.1.01', 'name' => 'Hutang Vendor', 'type' => 'liability', 'level' => 3, 'parent' => '2.1.00'],
            ['code' => '2.1.02', 'name' => 'Hutang Customer', 'type' => 'liability', 'level' => 3, 'parent' => '2.1.00'],
            ['code' => '2.1.03', 'name' => 'Uang Muka Pembelian', 'type' => 'liability', 'level' => 3, 'parent' => '2.1.00'],


            // LEVEL 3 - HUTANG BANK
            ['code' => '2.2.01', 'name' => 'Hutang Bank BCA', 'type' => 'liability', 'level' => 3, 'parent' => '2.2.00'],
            ['code' => '2.2.02', 'name' => 'Hutang Bank Mandiri', 'type' => 'liability', 'level' => 3, 'parent' => '2.2.00'],


            // LEVEL 3 - HUTANG STAFF
            ['code' => '2.3.01', 'name' => 'Hutang Gaji', 'type' => 'liability', 'level' => 3, 'parent' => '2.3.00'],
            ['code' => '2.3.02', 'name' => 'Hutang Bonus', 'type' => 'liability', 'level' => 3, 'parent' => '2.3.00'],
            ['code' => '2.3.03', 'name' => 'Hutang Reimburse', 'type' => 'liability', 'level' => 3, 'parent' => '2.3.00'],


            // ======================
            // LEVEL 2 - MODAL
            // ======================
            ['code' => '3.1.00', 'name' => 'Modal', 'type' => 'equity', 'level' => 2, 'parent' => '3.0.00'],


            // LEVEL 3 - MODAL
            ['code' => '3.1.01', 'name' => 'Modal Usaha', 'type' => 'equity', 'level' => 3, 'parent' => '3.1.00'],
            ['code' => '3.1.02', 'name' => 'Modal Pemilik', 'type' => 'equity', 'level' => 3, 'parent' => '3.1.00'],
            ['code' => '3.1.03', 'name' => 'Modal Lainnya', 'type' => 'equity', 'level' => 3, 'parent' => '3.1.00'],


            // ======================
            // LEVEL 2 - PENDAPATAN
            // ======================
            ['code' => '4.1.00', 'name' => 'Penjualan Produk', 'type' => 'income', 'level' => 2, 'parent' => '4.0.00'],
            ['code' => '4.2.00', 'name' => 'Pendapatan Lain', 'type' => 'income', 'level' => 2, 'parent' => '4.0.00'],


            // LEVEL 3 - PENDAPATAN
            ['code' => '4.1.01', 'name' => 'Penjualan Iphone', 'type' => 'income', 'level' => 3, 'parent' => '4.1.00'],
            ['code' => '4.1.02', 'name' => 'Penjualan Aksesoris', 'type' => 'income', 'level' => 3, 'parent' => '4.1.00'],
            ['code' => '4.2.01', 'name' => 'Pendapatan Lain', 'type' => 'income', 'level' => 3, 'parent' => '4.2.00'],


            // ======================
            // LEVEL 2 - BEBAN
            // ======================
            ['code' => '5.1.00', 'name' => 'HPP Penjualan', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.2.00', 'name' => 'Beban Operasional', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.3.00', 'name' => 'Beban Penjualan', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.4.00', 'name' => 'Administrasi', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.5.00', 'name' => 'Beban Keuangan', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],


            // LEVEL 3 - HPP
            ['code' => '5.1.01', 'name' => 'HPP Iphone', 'type' => 'expense', 'level' => 3, 'parent' => '5.1.00'],
            ['code' => '5.1.02', 'name' => 'HPP Aksesoris', 'type' => 'expense', 'level' => 3, 'parent' => '5.1.00'],


            // LEVEL 3 - OPERASIONAL
            ['code' => '5.2.01', 'name' => 'Beban Gaji Karyawan', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],
            ['code' => '5.2.02', 'name' => 'Beban Listrik', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],
            ['code' => '5.2.03', 'name' => 'Beban Air', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],
            ['code' => '5.2.04', 'name' => 'Beban Kontrakan', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],
            ['code' => '5.2.05', 'name' => 'Beban Transportasi', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],
            ['code' => '5.2.06', 'name' => 'Beban Konsumsi', 'type' => 'expense', 'level' => 3, 'parent' => '5.2.00'],


            // LEVEL 3 - PENJUALAN
            ['code' => '5.3.01', 'name' => 'Beban Marketing', 'type' => 'expense', 'level' => 3, 'parent' => '5.3.00'],
            ['code' => '5.3.02', 'name' => 'Beban Iklan', 'type' => 'expense', 'level' => 3, 'parent' => '5.3.00'],
            ['code' => '5.3.03', 'name' => 'Beban Komisi Penjualan', 'type' => 'expense', 'level' => 3, 'parent' => '5.3.00'],


            // LEVEL 3 - ADMIN
            ['code' => '5.4.01', 'name' => 'Beban Domain & Hosting', 'type' => 'expense', 'level' => 3, 'parent' => '5.4.00'],
            ['code' => '5.4.02', 'name' => 'Sistem', 'type' => 'expense', 'level' => 3, 'parent' => '5.4.00'],


            // LEVEL 3 - KEUANGAN
            ['code' => '5.5.01', 'name' => 'Beban Bank', 'type' => 'expense', 'level' => 3, 'parent' => '5.5.00'],
            ['code' => '5.5.02', 'name' => 'Beban Bunga Pinjaman', 'type' => 'expense', 'level' => 3, 'parent' => '5.5.00'],
            ['code' => '5.5.03', 'name' => 'Beban Potongan Bank', 'type' => 'expense', 'level' => 3, 'parent' => '5.5.00'],
        ];

        // Insert berurutan supaya parent ada dulu
        foreach ($coas as $coa) {
            $parentId = null;

            if (isset($coa['parent'])) {
                $parent = Coa::where('code', $coa['parent'])->first();
                $parentId = $parent?->id;
            }

            Coa::updateOrCreate(
                ['code' => $coa['code']],
                [
                    'name'        => $coa['name'],
                    'type'        => $coa['type'],
                    'parent_id'   => $parentId,
                    'level'       => $coa['level'],
                    'is_postable' => $coa['level'] === 3,
                    'is_active'   => true,
                    'description' => null,
                ]
            );
        }
    }
}
