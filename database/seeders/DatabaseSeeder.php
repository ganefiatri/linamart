<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MemberGroup::factory(2)->create();
        \App\Models\Member::factory(5)->create(['status' => 1])->each(function ($item, $key) {
            $item->balances()->create([
                'end_balance' => rand(500000, 1000000),
                'notes' => 'Initial dummy balance'
            ]);

            $item->user()->create([
                'name' => $item->name,
                'email' => $item->email,
                'email_verified_at' => $item->email_verified_at,
                'password' => Hash::make('12345678'),
                'role' => 'member'
            ]);

            if ($item->status > 0) {
                // open dummy shop for this user
                $shop = $item->shop()->create([
                    'name' => $item->name,
                    'slug' => Str::slug($item->name),
                    'phone' => $item->phone,
                    'district_id' => $item->district_id,
                    'address' => $item->address,
                    'postal_code' => $item->postal_code,
                    'status' => 1
                ]);

                if ($shop instanceof \App\Models\Shop) {
                    \App\Models\ProductCategory::factory(2)->create([
                        'shop_id' => $shop->id
                    ])->each(function ($category, $key) use ($shop) {
                        \App\Models\Product::factory(10)->create([
                            'shop_id' => $shop->id,
                            'category_id' => $category->id
                        ]);
                    });
                }
            }
        });
        // add balance
        \App\Models\Driver::factory(5)->create(['status' => 1])->each(function ($item, $key) {
            $item->user()->create([
                'name' => $item->name,
                'email' => $item->email,
                'email_verified_at' => $item->email_verified_at,
                'password' => Hash::make('12345678'),
                'role' => 'driver'
            ]);
        });
        // generate admin user
        \App\Models\User::factory(2)->create();
        // check district seeder if empty
        $tot_district = \App\Models\District::count();
        if ($tot_district <= 0) {
            $this->call(DistrictSeeder::class);
        }
        // check lookup seeder if empty
        $tot_lookup = \App\Models\Lookup::count();
        if ($tot_lookup <= 0) {
            $this->call(LookupSeeder::class);
        }
        // check option seeder if empty
        $tot_option = \App\Models\Option::count();
        if ($tot_option <= 0) {
            $this->call(OptionSeeder::class);
        }
        // check shipping seeder if empty
        $tot_shipping = \App\Models\Shipping::count();
        if ($tot_shipping <= 0) {
            $this->call(ShippingSeeder::class);
        }
    }
}
