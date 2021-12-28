<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'phone' => $this->faker->e164PhoneNumber(),
            'district_id' => rand(0, 100),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'status' => rand(0, 1)
        ];
    }
}
