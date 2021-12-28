<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        $pCategories = ProductCategory::pluck('shop_id', 'id')->toArray();
        $categories = array_keys($pCategories);
        $category_id = $categories[rand(0, (count($categories) - 1))];
        return [            
            'shop_id' => $pCategories[$category_id],
            'category_id' => $category_id,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(2),
            'unit' => 'gr',
            'weight' => rand(50, 100),
            'price' => rand(10000, 100000),
            'discount' => rand(0, 5000),
            'stock' => rand(0, 150),
            'active' => 1,
            'enabled' => 1,
            'hidden' => 0,
            'priority' => rand(0, 1),
            'viewed' => rand(0, 100)
        ];
    }
}
