<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234',
            'two_factor_secret' => null,
        ]);

        $this->call([
            AffirmationSeeder::class,
            HabitSeeder::class,
        ]);
    }
}
