<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingClosingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'closing_day_time',
                'label' => 'Jam Tutup Harian',
                'value' => '23:00',
                'type' => 'time',
                'group' => 'closing',
                'sort_order' => 1,
                'description' => 'Waktu closing untuk transaksi harian',
            ],
            [
                'key' => 'closing_months_time',
                'label' => 'Jam Tutup Bulanan',
                'value' => '23:59',
                'type' => 'time',
                'group' => 'closing',
                'sort_order' => 2,
                'description' => 'Waktu closing untuk akhir bulan',
            ],
            [
                'key' => 'closing_year_time',
                'label' => 'Jam Tutup Tahunan',
                'value' => '23:00',
                'type' => 'time',
                'group' => 'closing',
                'sort_order' => 3,
                'description' => 'Waktu closing untuk akhir tahun',
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
