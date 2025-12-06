<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 15000,
            'category' => 'Makanan',
        ]);

        Menu::create([
            'name' => 'Es Teh Manis',
            'price' => 5000,
            'category' => 'Minuman',
        ]);
    }
}
