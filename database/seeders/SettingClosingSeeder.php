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
            [
                'key' => 'closing_day_lock_after_hours',
                'label' => 'Batas Lock Closing Harian (Jam)',
                'value' => '12',
                'type' => 'number',
                'group' => 'closing',
                'sort_order' => 4,
                'description' => 'Batas waktu (dalam jam) setelah closing harian sebelum sistem mengunci data',
            ],
        ];

        foreach ($settings as $setting) {

            $exists = \App\Models\Setting::where('key', $setting['key'])->exists();

            if ($exists) {
                continue;
            }

            \App\Models\Setting::create($setting);
        }
    }
}
