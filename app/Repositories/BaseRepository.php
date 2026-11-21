<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @implements RepositoryInterface<TModel>
 */
abstract class BaseRepository implements RepositoryInterface
{
    /** @var TModel */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param  TModel  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     *
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     *
     * @return TModel|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->newQuery()->find($id);
    }

    /**
     * Find record by field and value
     *
     * @return TModel|null
     */
    public function findBy(string $field, string $value): ?Model
    {
        return $this->model->newQuery()->where($field, $value)->first();
    }

    /**
     * Get all records where field equals value
     *
     * @return Collection<int, TModel>
     */
    public function getBy(string $field, string $value): Collection
    {
        return $this->model->newQuery()->where($field, $value)->get();
    }

    /**
     * Create a new record
     *
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * Update a record
     *
     * @param  array<string, mixed>  $data
     * @return TModel|null
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
     * @param  array<int, string>  $columns
     * @return LengthAwarePaginator<int, TModel>
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage, $columns);
    }

    /**
     * Search records
     *
     * @return LengthAwarePaginator<int, TModel>
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage);
    }

    /**
     * Get the last created record
     *
     * @param  array<int, string>  $columns
     * @return TModel|null
     */
    public function getLast(array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->latest()->first($columns);
    }

    /**
     * Check if record exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get count of records
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get distinct values of a field
     *
     * @return Collection<int, TModel>
     */
    public function distinct(string $field): Collection
    {
        return $this->model->newQuery()->distinct()->get([$field]);
    }
}
