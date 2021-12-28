<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        $shops = Shop::where('status', 1)->pluck('id')->toArray();
        return [            
            'shop_id' => $shops[rand(0, (count($shops) - 1))],
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(2)
        ];
    }
}
