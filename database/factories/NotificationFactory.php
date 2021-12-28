<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::orderBy('id', 'asc')->pluck('id')->toArray();
        return [      
            'user_id' => rand(min($users), max($users)), 
            'message' => $this->faker->paragraph(2),
            'priority' => rand(1, 3),
            'status' => rand(0, 1)
        ];
    }
}
