<?php

namespace Database\Seeders;

use App\Enums\GoalStatusEnum;
use App\Enums\GoalTypeEnum;
use App\Enums\HabitStatusEnum;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$testUser) {
            $this->command->error('Test User not found! Make sure it is created in DatabaseSeeder.');
            return;
        }

        // --- Goals ---
        $goals = [
            [
                'title' => 'Complete Laravel Mastery Course',
                'description' => 'Finish the full Laravel online course by the end of the year.',
                'type' => GoalTypeEnum::Annual,
                'status' => GoalStatusEnum::Completed,
                'start_date' => $today,
                'end_date' => $today->copy()->endOfYear(),
            ],
            [
                'title' => 'Publish a Personal Blog',
                'description' => 'Write and publish 12 blog posts this year.',
                'type' => GoalTypeEnum::Annual,
                'status' => GoalStatusEnum::Archived,
                'start_date' => $today,
                'end_date' => $today->copy()->endOfYear(),
            ],
            [
                'title' => 'Work out',
                'description' => 'Work out 5 days a week.',
                'type' => GoalTypeEnum::Annual,
                'status' => GoalStatusEnum::Active,
                'start_date' => $today,
                'end_date' => $today->copy()->endOfYear(),
            ],
            [
                'title' => 'Write 4 Blog Posts',
                'description' => 'Write one blog post per week this month.',
                'type' => GoalTypeEnum::Monthly,
                'status' => GoalStatusEnum::Completed,
                'start_date' => $today->copy()->startOfMonth(),
                'end_date' => $today->copy()->endOfMonth(),
            ],
            [
                'title' => 'Improve Portfolio Design',
                'description' => 'Redesign portfolio website and add new projects.',
                'type' => GoalTypeEnum::Monthly,
                'status' => GoalStatusEnum::Active,
                'start_date' => $today->copy()->startOfMonth(),
                'end_date' => $today->copy()->endOfMonth(),
            ],
            [
                'title' => 'Complete Monthly Fitness Plan',
                'description' => 'Follow fitness plan 5 days per week.',
                'type' => GoalTypeEnum::Monthly,
                'status' => GoalStatusEnum::Active,
                'start_date' => $today->copy()->startOfMonth(),
                'end_date' => $today->copy()->endOfMonth(),
            ],
            [
                'title' => 'Exercise 3 Times',
                'description' => 'Complete 3 workout sessions this week.',
                'type' => GoalTypeEnum::Weekly,
                'status' => GoalStatusEnum::Active,
                'start_date' => $today->copy()->startOfWeek(),
                'end_date' => $today->copy()->endOfWeek(),
            ],
            [
                'title' => 'Work in one Project for Portfolio',
                'description' => 'Redesign project website.',
                'type' => GoalTypeEnum::Weekly,
                'status' => GoalStatusEnum::Active,
                'start_date' => $today->copy()->startOfWeek(),
                'end_date' => $today->copy()->endOfWeek(),
            ],
        ];

        foreach ($goals as $goalData) {
            $goal = Goal::factory()->create(array_merge($goalData, ['user_id' => $testUser->id]));

            // --- Habits ---
            $habitsData = match($goal->title) {
                'Complete Laravel Mastery Course' => [
                    ['name' => 'Watch 1 Laravel video', 'description' => 'Watch one tutorial video per day.', 'notes' => 'Review LaravelTip Youtube account.'],
                    ['name' => 'Practice coding exercises', 'description' => 'Complete coding exercises daily.', 'notes' => 'Practice in LeetCode'],
                ],
                'Publish a Personal Blog' => [
                    ['name' => 'Write a blog draft', 'description' => 'Write a draft post for the blog.', 'status' => HabitStatusEnum::Completed],
                    ['name' => 'Edit blog post', 'description' => 'Review and edit the blog post.'],
                ],
                'Work out', 'Complete Monthly Fitness Plan', 'Exercise 3 Times' => [
                    ['name' => 'Gym workout', 'description' => 'Perform strength training exercises.', 'notes' => 'Increase weight weekly'],
                    ['name' => 'Cardio session', 'description' => 'Do 30 minutes of cardio.'],
                ],
                'Write 4 Blog Posts' => [
                    ['name' => 'Write weekly blog', 'description' => 'Write one blog post per week.', 'status' => HabitStatusEnum::Paused],
                ],
                'Improve Portfolio Design', 'Work in one Project for Portfolio' => [
                    ['name' => 'Update portfolio', 'description' => 'Make one design improvement per day.'],
                ],
                default => [],
            };

            foreach ($habitsData as $habitData) {
                $habit = Habit::factory()->create(array_merge([
                    'goal_id' => $goal->id,
                    'status' => HabitStatusEnum::Active,
                ], $habitData));

                // --- HabitLogs ---
                $startLog = Carbon::yesterday();
                $endLog = Carbon::yesterday()->copy()->addWeek();

                for ($date = $startLog->copy(); $date->lte($endLog); $date->addDay()) {
                    HabitLog::factory()->create([
                        'habit_id' => $habit->id,
                        'date' => $date->toDateString(),
                        'completed' => true,
                        'notes' => 'Log for ' . $habit->name,
                    ]);
                }
            }
        }
    }
}
