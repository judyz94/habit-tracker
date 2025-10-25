<?php

namespace Tests\Feature\Api;

use App\Enums\GoalStatusEnum;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_own_goals(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Goal::factory()->count(2)->for($user)->create();
        Goal::factory()->count(1)->for($otherUser)->create();

        $this->actingAs($user)
            ->getJson('/api/goals')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['user_id' => $user->id])
            ->assertJsonMissing(['user_id' => $otherUser->id]);
    }

    public function test_user_can_create_goal(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Work out',
            'description' => 'Work out all week',
            'type' => GoalTypeEnum::Weekly->value,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'status' => GoalStatusEnum::Active->value,
        ];

        $this->actingAs($user)
            ->postJson('/api/goals', $payload)
            ->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Work out',
                'description' => 'Work out all week',
            ]);

        $this->assertDatabaseHas('goals', [
            'user_id' => $user->id,
            'title' => 'Work out'
        ]);
    }

    public function test_user_can_view_own_goal(): void
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->for($user)->create();

        $this->actingAs($user)
            ->getJson("/api/goals/$goal->id")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $goal->id]);
    }

    public function test_user_cannot_view_other_users_goal(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $goal = Goal::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->getJson("/api/goals/$goal->id")
            ->assertStatus(404);
    }

    public function test_user_can_update_own_goal(): void
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->for($user)->create();

        $payload = ['title' => 'Updated Goal'];

        $this->actingAs($user)
            ->putJson("/api/goals/$goal->id", $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Goal']);

        $this->assertDatabaseHas('goals', ['id' => $goal->id, 'title' => 'Updated Goal']);
    }

    public function test_user_cannot_update_other_users_goal(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $goal = Goal::factory()->for($otherUser)->create();

        $payload = ['title' => 'Hack Goal'];

        $this->actingAs($user)
            ->putJson("/api/goals/$goal->id", $payload)
            ->assertStatus(404);
    }

    public function test_user_can_delete_own_goal(): void
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson("/api/goals/$goal->id")
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Goal deleted successfully']);

        $this->assertDatabaseMissing('goals', ['id' => $goal->id]);
    }

    public function test_user_cannot_delete_other_users_goal(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $goal = Goal::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->deleteJson("/api/goals/$goal->id")
            ->assertStatus(404);

        $this->assertDatabaseHas('goals', ['id' => $goal->id]);
    }
}
