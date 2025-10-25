<?php

namespace Database\Factories;

use App\Models\Affirmation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Affirmation>
 */
class AffirmationFactory extends Factory
{
    protected $model = Affirmation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'text'     => $this->faker->sentence(8),
            'category' => $this->faker->optional()->randomElement([
                'Motivation',
                'Health',
                'Productivity',
                'Mindfulness',
                'Confidence',
            ]),
        ];
    }
}
