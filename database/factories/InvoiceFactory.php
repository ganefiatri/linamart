<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $member = Member::whereHas('shop')->first();
        return [
            'shop_id' => $member->shop->id,
            'member_id' => $member->id,
            'serie' => 'INV-',
            'nr' => rand(1000, 99999),
            'hash' => md5(time()),
            'base_income' => rand(100000, 300000),
            'shipping_fee' => rand(1000, 10000),
            'status' => ($status = rand(-1, 1)),
            'seller_name' => $member->shop->name,
            'seller_phone' => $member->shop->phone,
            'seller_address' => $member->shop->address,
            'seller_city' => $member->shop->district->city->name,
            'buyer_name' => $member->name,
            'buyer_phone' => $member->phone,
            'buyer_address' => $member->address,
            'buyer_city' => $member->district->name,
            'buyer_postal_code' => $member->postal_code,
            'paid_at' => ($status == 1) ? date('Y-m-d H:i:s') : null,
        ];
    }
}
