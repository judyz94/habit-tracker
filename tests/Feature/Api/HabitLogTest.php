<?php

namespace Tests\Feature\Api;

use App\Models\Goal;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HabitLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_user_can_list_all_logs(): void
    {
        HabitLog::factory()->count(3)->create();

        $response = $this->getJson('/api/habit-logs');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'habit_id', 'date', 'completed', 'notes'],
                ]
            ]);
    }

    public function test_user_can_list_logs_for_a_given_habit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create(['goal_id' => $goal->id]);

        HabitLog::factory()->count(2)->create(['habit_id' => $habit->id]);
        HabitLog::factory()->count(1)->create();

        $response = $this->getJson('/api/habit-logs?habit_id=' . $habit->id);

        $response->assertOk()
            ->assertJsonPath('message', 'Habit logs retrieved successfully for the given habit')
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_create_a_log(): void
    {
        $habit = Habit::factory()->create();

        $data = [
            'habit_id' => $habit->id,
            'date' => now()->toDateString(),
            'completed' => true,
            'notes' => 'Completed meditation with focus and calm.',
        ];

        $response = $this->postJson('/api/habit-logs', $data);

        $response->assertCreated()
            ->assertJsonPath('message', 'Habit log created successfully');

        $this->assertDatabaseHas('habit_logs', [
            'notes' => 'Completed meditation with focus and calm.',
            'habit_id' => $habit->id,
        ]);
    }

    public function test_user_can_update_a_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create(['goal_id' => $goal->id]);
        $log = HabitLog::factory()->create([
            'habit_id' => $habit->id,
            'date' => now()->format('Y-m-d'),
            'completed' => false,
        ]);

        $data = [
            'completed' => true,
        ];

        $response = $this->putJson("/api/habit-logs/{$log->id}", $data);

        $response->assertOk()
            ->assertJsonPath('message', 'Habit log updated successfully')
            ->assertJsonPath('data.completed', true);

        $this->assertDatabaseHas('habit_logs', [
            'id' => $log->id,
            'completed' => true,
        ]);
    }

    public function test_it_returns_404_if_trying_to_update_non_existent_log(): void
    {
        $response = $this->putJson('/api/habit-logs/9999', ['completed' => true]);
        $response->assertNotFound()
            ->assertJsonPath('message', 'Habit log not found or not accessible');
    }

    public function test_user_can_delete_a_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $goal = Goal::factory()->create(['user_id' => $user->id]);
        $habit = Habit::factory()->create(['goal_id' => $goal->id]);
        $log = HabitLog::factory()->create(['habit_id' => $habit->id]);

        $response = $this->deleteJson("/api/habit-logs/$log->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Habit log deleted successfully');

        $this->assertDatabaseMissing('habit_logs', [
            'id' => $log->id,
        ]);
    }
}
