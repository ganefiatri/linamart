<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::get()->each(function ($user, $key) {
            \App\Models\Notification::factory(20)->create(['user_id' => $user->id]);
        });
    }
}
