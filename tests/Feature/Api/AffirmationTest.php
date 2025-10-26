<?php

namespace Tests\Feature\Api;

use App\Models\Affirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_own_affirmations(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Affirmation::factory()->count(2)->for($user)->create();
        Affirmation::factory()->count(1)->for($otherUser)->create();

        $this->actingAs($user)
            ->getJson('/api/affirmations')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['user_id' => $user->id])
            ->assertJsonMissing(['user_id' => $otherUser->id]);
    }

    public function test_user_can_create_affirmation(): void
    {
        $user = User::factory()->create();

        $payload = [
            'text' => 'Stay hard',
            'category' => 'Motivation',
        ];

        $this->actingAs($user)
            ->postJson('/api/affirmations', $payload)
            ->assertStatus(201)
            ->assertJsonFragment([
                'text' => 'Stay hard',
                'category' => 'Motivation',
            ]);

        $this->assertDatabaseHas('affirmations', [
            'user_id' => $user->id,
            'text' => 'Stay hard',
        ]);
    }

    public function test_user_can_view_own_affirmation(): void
    {
        $user = User::factory()->create();
        $affirmation = Affirmation::factory()->for($user)->create();

        $this->actingAs($user)
            ->getJson("/api/affirmations/$affirmation->id")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $affirmation->id]);
    }

    public function test_user_cannot_view_other_users_affirmation(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $affirmation = Affirmation::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->getJson("/api/affirmations/$affirmation->id")
            ->assertStatus(404);
    }

    public function test_user_can_update_own_affirmation(): void
    {
        $user = User::factory()->create();
        $affirmation = Affirmation::factory()->for($user)->create();

        $payload = ['text' => 'Stay hard and constant'];

        $this->actingAs($user)
            ->putJson("/api/affirmations/$affirmation->id", $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['text' => 'Stay hard and constant']);

        $this->assertDatabaseHas('affirmations', ['id' => $affirmation->id, 'text' => 'Stay hard and constant']);
    }

    public function test_user_cannot_update_other_users_affirmation(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $affirmation = Affirmation::factory()->for($otherUser)->create();

        $payload = ['text' => 'I am focused'];

        $this->actingAs($user)
            ->putJson("/api/affirmations/$affirmation->id", $payload)
            ->assertStatus(404);
    }

    public function test_user_can_delete_own_affirmation(): void
    {
        $user = User::factory()->create();
        $affirmation = Affirmation::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson("/api/affirmations/$affirmation->id")
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Affirmation deleted successfully']);

        $this->assertDatabaseMissing('affirmations', ['id' => $affirmation->id]);
    }

    public function test_user_cannot_delete_other_users_affirmation(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $affirmation = Affirmation::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->deleteJson("/api/affirmations/$affirmation->id")
            ->assertStatus(404);

        $this->assertDatabaseHas('affirmations', ['id' => $affirmation->id]);
    }
}
