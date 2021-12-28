<?php

namespace Database\Seeders;

use App\Models\ProductUnit;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'pcs' => 'Pcs',
            'gr' => 'Gram',
            'kg' => 'Kilogram',
            'ml' => 'Mililiter',
            'cm' => 'Centimeter',
            'mm' => 'Milimeter',
            'm' => 'Meter'
        ];

        foreach ($items as $code => $title) {
            ProductUnit::create([
                'code' => $code,
                'title' => $title
            ]); 
        }
    }
}
