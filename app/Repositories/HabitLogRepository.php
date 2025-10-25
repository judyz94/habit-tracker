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
     * Get all habit logs, optionally filtered by habit_id
     */
    public function getAll(?int $habitId = null): Collection
    {
        $query = $this->model
            ->with('habit')
            ->latest();

        if ($habitId) {
            $query->where('habit_id', $habitId);
        }

        return $query->get();
    }

    public function findOrFail(int $id): HabitLog
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): HabitLog
    {
        return $this->model->create($data);
    }

    public function delete(int $id): bool
    {
        $log = $this->findOrFail($id);

        return $log->delete();
    }
}
