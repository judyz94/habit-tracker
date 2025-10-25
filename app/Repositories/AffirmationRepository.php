<?php

namespace App\Repositories;

use App\Models\Affirmation;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AffirmationRepository implements RepositoryInterface
{
    protected Affirmation $model;

    public function __construct(Affirmation $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function findOrFail(int $id): Affirmation
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Affirmation
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Affirmation
    {
        $affirmation = $this->findOrFail($id);
        $affirmation->update($data);

        return $affirmation;
    }

    public function delete(int $id): bool
    {
        $affirmation = $this->findOrFail($id);

        return $affirmation->delete();
    }
}
