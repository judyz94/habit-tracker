<?php

namespace Database\Seeders;

use App\Models\Affirmation;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234'
        ]);

        Goal::factory(6)->create();
        Habit::factory(10)->create();
        Affirmation::factory(5)->create();
    }
}
