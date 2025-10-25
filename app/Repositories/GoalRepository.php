<?php

namespace App\Repositories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Collection;

class GoalRepository
{
    protected Goal $model;

    public function __construct(Goal $model)
    {
        $this->model = $model;
    }

    /**
     * Get all goals, optionally filtered by user_id
     */
    public function getAll(?int $userId = null): Collection
    {
        $query = $this->model
            ->with('habits')
            ->latest();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }

    public function findOrFail(int $id, int $userId): Goal
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('habits')
            ->findOrFail($id);
    }

    public function create(array $data, int $userId): Goal
    {
        $data['user_id'] = $userId;

        $goal = $this->model->create($data);

        return $goal->load('habits');
    }

    public function update(int $id, int $userId, array $data): Goal
    {
        $goal = $this->findOrFail($id, $userId);
        $goal->update($data);

        return $goal;
    }

    public function delete(int $id, int $userId): bool
    {
        $goal = $this->findOrFail($id, $userId);

        return $goal->delete();
    }
}
