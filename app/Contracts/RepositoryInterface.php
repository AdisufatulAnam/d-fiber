<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all(): Collection;

    /**
     * Find record by ID
     */
    public function find(int $id): ?Model;

    /**
     * Find record by field and value
     */
    public function findBy(string $field, string $value): ?Model;

    /**
     * Get all records where field equals value
     */
    public function getBy(string $field, string $value): Collection;

    /**
     * Create a new record
     */
    public function create(array $data): Model;

    /**
     * Update a record
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Get records with pagination
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Search records
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get the last created record
     */
    public function getLast(array $columns = ['*']): ?Model;

    /**
     * Check if record exists
     */
    public function exists(int $id): bool;

    /**
     * Get count of records
     */
    public function count(): int;

    /**
     * Get distinct values of a field
     */
    public function distinct(string $field): Collection;
}