<?php

namespace Database\Factories;

use App\Models\MemberGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [            
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(2)
        ];
    }
}
