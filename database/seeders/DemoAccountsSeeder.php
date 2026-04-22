<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aryapann12@gmail.com'],
            [
                'name' => 'Arya Pannadana (User)',
                'username' => 'HNile',
                'first_name' => 'Arya',
                'last_name' => 'Pannadana (User)',
                'phone' => '087775933022',
                'date_of_birth' => '2006-05-04',
                'role' => 'buyer',
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'ShopEase Owner',
                'username' => 'Owner',
                'first_name' => 'ShopEase',
                'last_name' => 'Owner',
                'phone' => '081234567890',
                'date_of_birth' => '2000-01-01',
                'role' => 'owner',
                'password' => Hash::make('owner123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Arya Pannadana (Admin)',
                'username' => 'Admin',
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
