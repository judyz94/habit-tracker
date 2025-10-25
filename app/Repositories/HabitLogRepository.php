<?php

namespace App\Repositories;

use App\Models\HabitLog;
use Illuminate\Database\Eloquent\Collection;

class HabitLogRepository
{
    protected HabitLog $model;

    public function __construct(HabitLog $model)
    {
        $this->model = $model;
    }

    /**
     * Get all habit logs, optionally filtered by habit_id or user_id
     */
    public function getAll(?int $habitId = null): Collection
    {
        $userId = auth()->id();

        $query = $this->model
            ->with('habit')
            ->whereHas('habit.goal', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest();

        if ($habitId) {
            $query->where('habit_id', $habitId);
        }

        return $query->get();
    }

    public function findOrFail(int $id, int $userId): HabitLog
    {
        return $this->model
            ->whereHas('habit.goal', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('habit')
            ->findOrFail($id);
    }

    public function create(array $data): HabitLog
    {
        $log = $this->model->create($data);

        return $log->load('habit');
    }

    public function delete(int $id, int $userId): bool
    {
        $log = $this->findOrFail($id, $userId);

        return $log->delete();
    }
}
