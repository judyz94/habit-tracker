<?php

namespace App\Repositories;

use App\Models\Habit;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HabitRepository implements RepositoryInterface
{
    protected Habit $model;

    public function __construct(Habit $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->with('logs')->get();
    }

    public function findOrFail(int $id): Habit
    {
        return $this->model->with('logs')->findOrFail($id);
    }

    public function create(array $data): Habit
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Habit
    {
        $habit = $this->findOrFail($id);
        $habit->update($data);

        return $habit;
    }

    public function delete(int $id): bool
    {
        $habit = $this->findOrFail($id);

        return $habit->delete();
    }
}
