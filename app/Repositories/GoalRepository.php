<?php

namespace App\Repositories;

use App\Models\Goal;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GoalRepository implements RepositoryInterface
{
    protected Goal $model;

    public function __construct(Goal $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model
            ->where('user_id', auth()->id())
            ->with('habits')
            ->latest()
            ->get();
    }

    public function findOrFail(int $id): Goal
    {
        return $this->model->with('habits')->findOrFail($id);
    }

    public function create(array $data): Goal
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Goal
    {
        $goal = $this->findOrFail($id);
        $goal->update($data);

        return $goal;
    }

    public function delete(int $id): bool
    {
        $goal = $this->findOrFail($id);

        return $goal->delete();
    }
}
