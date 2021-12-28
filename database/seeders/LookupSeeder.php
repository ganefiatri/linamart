<?php

namespace Database\Seeders;

use App\Models\Lookup;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['Inactive', 'UserStatus', 0, 1],
            ['Active', 'UserStatus', 1, 2],
            ['Pending', 'ClientStatus', 0, 1],
            ['Active', 'ClientStatus', 1, 2],
            ['Suspended', 'ClientStatus', -1, 3],
            ['Canceled', 'ClientStatus', -2, 4],
            ['Male', 'Gender', 1, 1],
            ['Female', 'Gender', 2, 2],
            ['Pending', 'OrderStatus', 0, 1],
            ['Approved', 'OrderStatus', 1, 2],
            ['Shipped', 'OrderStatus', 2, 3],
            ['Delivered', 'OrderStatus', 3, 4],
            ['Completed', 'OrderStatus', 4, 5],
            ['Undelivered', 'OrderStatus', -1, 6],
            ['Canceled', 'OrderStatus', -2, 7],
            ['Unpaid', 'InvoiceStatus', 0, 1],
            ['Paid', 'InvoiceStatus', 1, 2],
            ['Refunded', 'InvoiceStatus', -1, 3],
            ['Draft', 'ProductStatus', 0, 1],
            ['Published', 'ProductStatus', 1, 2],
            ['Pending', 'ShopStatus', 0, 1],
            ['Active', 'ShopStatus', 1, 2],
            ['Suspended', 'ShopStatus', -1, 3],
            ['Canceled', 'ShopStatus', -2, 4],
        ];

        foreach ($items as $i => $item) {
            Lookup::create([
                'name' => $item[0],
                'code' => $item[2],
                'type' => $item[1],
                'position' => $item[3]
            ]); 
        }
    }
}
