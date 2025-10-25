<?php

namespace Database\Factories;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HabitLog>
 */
class HabitLogFactory extends Factory
{
    protected $model = HabitLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'habit_id' => Habit::factory(),
            'date' => $this->faker->date(),
            'completed' => $this->faker->boolean(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
