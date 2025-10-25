<?php

namespace App\Repositories;

use App\Models\Habit;
use Illuminate\Database\Eloquent\Collection;

class HabitRepository
{
    protected Habit $model;

    public function __construct(Habit $model)
    {
        $this->model = $model;
    }

    /**
     * Get all habits, optionally filtered by goal_id or user_id
     */
    public function getAll(?int $goalId = null): Collection
    {
        $userId = auth()->id();

        $query = $this->model
            ->with(['logs', 'goal'])
            ->whereHas('goal', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest();

        if ($goalId) {
            $query->where('goal_id', $goalId);
        }

        return $query->get();
    }

    public function findOrFail(int $id, int $userId): Habit
    {
        return $this->model
            ->whereHas('goal', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['logs', 'goal'])
            ->findOrFail($id);
    }

    public function create(array $data): Habit
    {
        $habit = $this->model->create($data);

        return $habit->load(['logs', 'goal']);
    }

    public function update(int $id, int $userId, array $data): Habit
    {
        $habit = $this->findOrFail($id, $userId);
        $habit->update($data);

        return $habit;
    }

    public function delete(int $id, int $userId): bool
    {
        $habit = $this->findOrFail($id, $userId);

        return $habit->delete();
    }
}
