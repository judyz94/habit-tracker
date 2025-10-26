<?php

namespace Database\Seeders;

use App\Models\Affirmation;
use App\Models\User;
use Illuminate\Database\Seeder;

class AffirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$testUser) {
            $this->command->error('Test User not found! Make sure it is created in DatabaseSeeder.');
            return;
        }

        $affirmations = [
            ['text' => 'I am confident in my abilities.', 'category' => 'confidence'],
            ['text' => 'I am worthy of success.', 'category' => 'worth'],
            ['text' => 'I embrace challenges and grow.', 'category' => 'growth'],
            ['text' => 'I attract positivity and opportunities.', 'category' => 'positivity'],
        ];

        foreach ($affirmations as $affirmation) {
            Affirmation::create(array_merge($affirmation, ['user_id' => $testUser->id]));
        }
    }
}
