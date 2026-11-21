<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
interface RepositoryInterface
{
    /**
     * Get all records
     *
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * Find record by ID
     *
     * @return TModel|null
     */
    public function find(int $id): ?Model;

    /**
     * Find record by field and value
     *
     * @return TModel|null
     */
    public function findBy(string $field, string $value): ?Model;

    /**
     * Get all records where field equals value
     *
     * @return Collection<int, TModel>
     */
    public function getBy(string $field, string $value): Collection;

    /**
     * Create a new record
     *
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data): Model;

    /**
     * Update a record
     *
     * @param  array<string, mixed>  $data
     * @return TModel|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Get records with pagination
     *
     * @param  array<int, string>  $columns
     * @return LengthAwarePaginator<int, TModel>
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Search records
     *
     * @return LengthAwarePaginator<int, TModel>
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get the last created record
     *
     * @param  array<int, string>  $columns
     * @return TModel|null
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
     *
     * @return Collection<int, TModel>
     */
    public function distinct(string $field): Collection;
}
