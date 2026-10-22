<?php

namespace App\Contracts;

interface ServiceInterface
{
    /**
     * Get all records (when applicable)
     */
    public function all();

    /**
     * Get record by ID (when applicable)
     */
    public function find(int $id);

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $filters = []);

    /**
     * Create a new record with business logic
     */
    public function create(array $data);

    /**
     * Update a record with business logic
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     */
    public function delete(int $id);
}