<?php

namespace App\Contracts;

interface ServiceInterface
{
    /**
     * Get all records (when applicable)
     *
     * @return mixed
     */
    public function all();

    /**
     * Get record by ID (when applicable)
     *
     * @return mixed
     */
    public function find(int $id);

    /**
     * Get paginated records
     *
     * @param  array<string, mixed>  $filters
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $filters = []);

    /**
     * Create a new record with business logic
     *
     * @param  array<string, mixed>  $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a record with business logic
     *
     * @param  array<string, mixed>  $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     *
     * @return mixed
     */
    public function delete(int $id);
}
