<?php

namespace Tests\Feature\Api;

use App\Enums\HabitStatusEnum;
use App\Enums\WeekDayEnum;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HabitTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_user_can_list_all_habits(): void
    {
        Habit::factory()->count(3)->create();

        $response = $this->getJson('/api/habits');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'description', 'status']
                ]
            ]);
    }

    public function test_user_can_list_habits_for_a_given_goal(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);

        Habit::factory()->count(2)->create(['goal_id' => $goal->id]);
        Habit::factory()->count(1)->create();

        $response = $this->getJson('/api/habits?goal_id=' . $goal->id);

        $response->assertOk()
            ->assertJsonPath('message', 'Habits retrieved successfully for the given goal')
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_create_a_habit(): void
    {
        $goal = Goal::factory()->create();

        $data = [
            'goal_id' => $goal->id,
            'name' => 'Morning Meditation',
            'description' => '10 minutes of meditation before work',
            'schedule_time' => '07:30',
            'repeat_days' => [
                WeekDayEnum::Monday->value,
                WeekDayEnum::Wednesday->value,
                WeekDayEnum::Friday->value,
            ],            'min_action' => 'Sit and breathe',
            'min_time' => 10,
            'status' => HabitStatusEnum::Active->value,
        ];

        $response = $this->postJson('/api/habits', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Habit created successfully')
            ->assertJsonPath('data.name', 'Morning Meditation');

        $this->assertDatabaseHas('habits', [
            'name' => 'Morning Meditation',
            'goal_id' => $goal->id,
        ]);
    }

    public function test_it_can_show_a_habit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create(['goal_id' => $goal->id]);

        $response = $this->getJson("/api/habits/$habit->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Habit retrieved successfully')
            ->assertJsonPath('data.id', $habit->id);
    }

    public function test_it_returns_404_if_habit_not_found(): void
    {
        $response = $this->getJson('/api/habits/9999');
        $response->assertNotFound()
            ->assertJsonPath('message', 'Habit not found or not accessible');
    }

    public function test_user_can_update_a_habit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create([
            'goal_id' => $goal->id,
            'name' => 'Old Name',
        ]);

        $data = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'min_time' => 20,
        ];

        $response = $this->putJson("/api/habits/$habit->id", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Habit updated successfully')
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('habits', ['name' => 'Updated Name']);
    }

    public function test_it_returns_404_if_trying_to_update_non_existent_habit(): void
    {
        $response = $this->putJson('/api/habits/9999', ['name' => 'New']);
        $response->assertNotFound()
            ->assertJsonPath('message', 'Habit not found or not accessible');
    }

    public function test_user_can_delete_a_habit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create(['goal_id' => $goal->id]);

        $response = $this->deleteJson("/api/habits/$habit->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Habit deleted successfully');

        $this->assertDatabaseMissing('habits', [
            'id' => $habit->id,
        ]);
    }
}
