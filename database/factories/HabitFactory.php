<?php

namespace Database\Factories;

use App\Enums\HabitStatusEnum;
use App\Enums\WeekDayEnum;
use App\Models\Goal;
use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habit>
 */
class HabitFactory extends Factory
{
    protected $model = Habit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'schedule_time' => $this->faker->time('H:i'),
            'repeat_days' => $this->faker->optional()->randomElements(
                array_column(WeekDayEnum::cases(), 'value'),
                $this->faker->numberBetween(1, 7)
            ),
            'min_action' => $this->faker->sentence(3),
            'min_time' => $this->faker->numberBetween(5, 60), // time in minutes
            'environment_design' => $this->faker->optional()->sentence(),
            'reward' => $this->faker->optional()->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(array_column(HabitStatusEnum::cases(), 'value')),
        ];
    }
}
