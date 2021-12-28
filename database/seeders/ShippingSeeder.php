<?php

namespace Database\Seeders;

use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['Dekat', 0, 5, 10000],
            ['Sedang', 6, 10, 15000],
            ['Jauh', 11, 15, 20000]
        ];

        foreach ($items as $i => $item) {
            Shipping::create([
                'title' => $item[0],
                'distance_from' => $item[1],
                'distance_to' => $item[2],
                'cost' => $item[3]
            ]); 
        }
    }
}
