<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run()
    {
        Table::create([
            'name' => 'Meja 1',
            'slug' => 'meja-1'
        ]);

        Table::create([
            'name' => 'Meja 2',
            'slug' => 'meja-2'
        ]);

        Table::create([
            'name' => 'Meja 3',
            'slug' => 'meja-3'
        ]);
    }
}
