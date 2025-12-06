<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@restoqr.local'],
            [
                'name'     => 'Admin Resto',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Kitchen user
        User::updateOrCreate(
            ['email' => 'kitchen@restoqr.local'],
            [
                'name'     => 'Kitchen Staff',
                'password' => Hash::make('kitchen123'),
                'role'     => 'kitchen',
            ]
        );
    }
}
