<?php

namespace App\Repositories;

use App\Models\Affirmation;
use Illuminate\Database\Eloquent\Collection;

class AffirmationRepository
{
    protected Affirmation $model;

    public function __construct(Affirmation $model)
    {
        $this->model = $model;
    }

    /**
     * Get all affirmations, optionally filtered by user_id
     */
    public function getAll(?int $userId = null): Collection
    {
        $query = $this->model->latest();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }

    public function findOrFail(int $id, int $userId): Affirmation
    {
        return $this->model
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function create(array $data, int $userId): Affirmation
    {
        $data['user_id'] = $userId;

        return $this->model->create($data);
    }

    public function update(int $id, int $userId, array $data): Affirmation
    {
        $affirmation = $this->findOrFail($id, $userId);
        $affirmation->update($data);

        return $affirmation;
    }

    public function delete(int $id, int $userId): bool
    {
        $affirmation = $this->findOrFail($id, $userId);

        return $affirmation->delete();
    }
}
