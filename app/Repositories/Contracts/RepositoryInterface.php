<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Retrieve all records.
     */
    public function getAll(): Collection;

    /**
     * Find a record by its ID or fail.
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record by its ID.
     */
    public function delete(int $id): bool;
}
