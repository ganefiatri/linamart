<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = [1 => 'male', 2 => 'female'];
        $gen = rand(1, 2);
        return [
            'member_id' => uniqid(),
            'name' => $this->faker->name($genders[$gen]),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'group_id' => $gen,
            'phone' => $this->faker->e164PhoneNumber(),
            'address' => $this->faker->address(),
            'district_id' => rand(1, 100),
            'postal_code' => $this->faker->postcode(),
            'gender' => $gen,
            'currency' => 'IDR',
            'lang' => 'ID',
            'notes' => $this->faker->paragraph(2),
            'status' => rand(0, 1)
        ];
    }
}
