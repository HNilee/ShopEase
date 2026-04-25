<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Buyer (HNile)
        User::updateOrCreate(
            ['username' => 'HNile'], // Cari berdasarkan username agar tidak bentrok
            [
                'email' => 'aryapann12@gmail.com',
                'name' => 'Arya Pannadana (User)',
                'first_name' => 'Arya',
                'last_name' => 'Pannadana (User)',
                'phone' => '087775933022',
                'date_of_birth' => '2006-05-04',
                'role' => 'buyer',
                'password' => Hash::make('123'),
            ]
        );

        // 2. Akun Owner
        User::updateOrCreate(
            ['username' => 'Owner'], // Cari berdasarkan username
            [
                'email' => 'owner@gmail.com',
                'name' => 'ShopEase Owner',
                'first_name' => 'ShopEase',
                'last_name' => 'Owner',
                'phone' => '081234567890',
                'date_of_birth' => '2000-01-01',
                'role' => 'owner',
                'password' => Hash::make('owner123'),
            ]
        );

        // 3. Akun Admin
        User::updateOrCreate(
            ['username' => 'Admin'], // Cari berdasarkan username
            [
                'email' => 'admin@gmail.com',
                'name' => 'Arya Pannadana (Admin)',
                'first_name' => 'Arya',
                'last_name' => 'Pannadana (Admin)',
                'phone' => '083808479781',
                'date_of_birth' => '2006-05-04',
                'role' => 'admin',
                'password' => Hash::make('321'),
            ]
        );
    }
}