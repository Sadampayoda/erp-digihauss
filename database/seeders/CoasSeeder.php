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

            // ======================
            // LEVEL 1
            // ======================
            ['code' => '1.0.00', 'name' => 'ASET',       'type' => 'asset',     'level' => 1],
            ['code' => '2.0.00', 'name' => 'KEWAJIBAN',  'type' => 'liability', 'level' => 1],
            ['code' => '3.0.00', 'name' => 'MODAL',      'type' => 'equity',    'level' => 1],
            ['code' => '4.0.00', 'name' => 'PENDAPATAN', 'type' => 'income',    'level' => 1],
            ['code' => '5.0.00', 'name' => 'BEBAN',      'type' => 'expense',   'level' => 1],

            // ======================
            // LEVEL 2 - ASET
            // ======================
            ['code' => '1.1.00', 'name' => 'Kas & Bank',        'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],
            ['code' => '1.2.00', 'name' => 'Piutang Usaha',    'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],
            ['code' => '1.3.00', 'name' => 'Persediaan',       'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],
            ['code' => '1.4.00', 'name' => 'Aset Tetap',       'type' => 'asset', 'level' => 2, 'parent' => '1.0.00'],

            // ======================
            // LEVEL 2 - KEWAJIBAN
            // ======================
            ['code' => '2.1.00', 'name' => 'Hutang Usaha',      'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],
            ['code' => '2.2.00', 'name' => 'Uang Muka Customer', 'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],
            ['code' => '2.3.00', 'name' => 'Uang Muka Supplier', 'type' => 'liability', 'level' => 2, 'parent' => '2.0.00'],

            // ======================
            // LEVEL 2 - MODAL
            // ======================
            ['code' => '3.1.00', 'name' => 'Modal Usaha', 'type' => 'equity', 'level' => 2, 'parent' => '3.0.00'],

            // ======================
            // LEVEL 2 - PENDAPATAN
            // ======================
            ['code' => '4.1.00', 'name' => 'Penjualan',       'type' => 'income', 'level' => 2, 'parent' => '4.0.00'],
            ['code' => '4.3.00', 'name' => 'Retur Penjualan', 'type' => 'income', 'level' => 2, 'parent' => '4.0.00'],

            // ======================
            // LEVEL 2 - BEBAN
            // ======================
            ['code' => '5.1.00', 'name' => 'Harga Pokok Penjualan', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.3.00', 'name' => 'Beban Sewa & Utilitas', 'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.4.00', 'name' => 'Beban Gaji & SDM',      'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],
            ['code' => '5.5.00', 'name' => 'Beban Pemasaran',      'type' => 'expense', 'level' => 2, 'parent' => '5.0.00'],

            // ======================
            // LEVEL 3 - ASET
            // ======================
            ['code' => '1.1.01', 'name' => 'Kas',                     'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],
            ['code' => '1.1.02', 'name' => 'Bank BCA',                'type' => 'asset', 'level' => 3, 'parent' => '1.1.00'],
            ['code' => '1.2.01', 'name' => 'Piutang Usaha',           'type' => 'asset', 'level' => 3, 'parent' => '1.2.00'],
            ['code' => '1.3.01', 'name' => 'Persediaan Barang',       'type' => 'asset', 'level' => 3, 'parent' => '1.3.00'],
            ['code' => '1.3.02', 'name' => 'Persediaan Tukar Tambah', 'type' => 'asset', 'level' => 3, 'parent' => '1.3.00'],

            // ======================
            // LEVEL 3 - KEWAJIBAN
            // ======================
            ['code' => '2.2.01', 'name' => 'Uang Muka Penjualan', 'type' => 'liability', 'level' => 3, 'parent' => '2.2.00'],
            ['code' => '2.3.01', 'name' => 'Uang Muka Pembelian', 'type' => 'liability', 'level' => 3, 'parent' => '2.3.00'],

            // ======================
            // LEVEL 3 - MODAL
            // ======================
            ['code' => '3.1.01', 'name' => 'Modal Pemilik', 'type' => 'equity', 'level' => 3, 'parent' => '3.1.00'],

            // ======================
            // LEVEL 3 - PENDAPATAN
            // ======================
            ['code' => '4.1.01', 'name' => 'Penjualan iPhone',       'type' => 'income', 'level' => 3, 'parent' => '4.1.00'],
            ['code' => '4.1.02', 'name' => 'Penjualan Tukar Tambah', 'type' => 'income', 'level' => 3, 'parent' => '4.1.00'],
            ['code' => '4.3.01', 'name' => 'Retur Penjualan',        'type' => 'income', 'level' => 3, 'parent' => '4.3.00'],

            // ======================
            // LEVEL 3 - BEBAN
            // ======================
            ['code' => '5.1.01', 'name' => 'HPP Barang',          'type' => 'expense', 'level' => 3, 'parent' => '5.1.00'],
            ['code' => '5.3.01', 'name' => 'Beban Sewa / Kontrak', 'type' => 'expense', 'level' => 3, 'parent' => '5.3.00'],
            ['code' => '5.4.01', 'name' => 'Gaji Karyawan',       'type' => 'expense', 'level' => 3, 'parent' => '5.4.00'],
            ['code' => '5.5.01', 'name' => 'Iklan & Konten',      'type' => 'expense', 'level' => 3, 'parent' => '5.5.00'],
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
