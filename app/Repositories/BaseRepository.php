<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find record by field and value
     *
     * @param string $field
     * @param string $value
     * @return Model|null
     */
    public function findBy(string $field, string $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     * Get all records where field equals value
     *
     * @param string $field
     * @param string $value
     * @return Collection
     */
    public function getBy(string $field, string $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model
    {
        $record = $this->find($id);

        if ($record) {
            $record->update($data);
            return $record;
        }

        return null;
    }

    /**
     * Delete a record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $record = $this->find($id);

        if ($record) {
            return $record->delete();
        }

        return false;
    }

    /**
     * Get records with pagination
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Search records
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Get the last created record
     *
     * @param array $columns
     * @return Model|null
     */
    public function getLast(array $columns = ['*']): ?Model
    {
        return $this->model->latest()->first($columns);
    }

    /**
     * Check if record exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get count of records
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get distinct values of a field
     *
     * @param string $field
     * @return Collection
     */
    public function distinct(string $field): Collection
    {
        return $this->model->distinct()->get([$field]);
    }
}