<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kot17.com'],
            [
                'name' => 'Admin Kot17',
                'phone' => '010000000',
                'role' => 'admin',
                'is_active' => true,
                'password' => Hash::make('12345678'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'treasurer@kot17.com'],
            [
                'name' => 'Treasurer Kot17',
                'phone' => '011000000',
                'role' => 'treasurer',
                'is_active' => true,
                'password' => Hash::make('12345678'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'collector@kot17.com'],
            [
                'name' => 'Collector Kot17',
                'phone' => '012000000',
                'role' => 'collector',
                'is_active' => true,
                'password' => Hash::make('12345678'),
            ]
        );

        // User::updateOrCreate(
        //     ['email' => 'Utilities Treasurer@kot17.com'],
        //     [
        //         'name' => 'Utilities Treasurer Kot17',
        //         'phone' => '012000000',
        //         'role' => 'Utilities Treasurer',
        //         'is_active' => true,
        //         'password' => Hash::make('12345678'),
        //     ]
        // );

        User::updateOrCreate(
            ['email' => 'member@kot17.com'],
            [
                'name' => 'Member Kot17',
                'phone' => '013000000',
                'role' => 'member',
                'is_active' => true,
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
