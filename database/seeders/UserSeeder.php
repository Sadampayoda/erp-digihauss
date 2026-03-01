<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@demo.com',
            ],
            [
                'name'              => 'admin',
                'password'          => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'alby@gmail.com',
            ],
            [
                'name'              => 'alby',
                'password'          => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );
    }
}
