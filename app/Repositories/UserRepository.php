<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Get active users
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection
    {
        return $this->model->where('active', true)->get();
    }

    /**
     * Get users by role
     *
     * @param string $role
     * @return Collection
     */
    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }

    /**
     * Search users by name or email
     *
     * @param string $query
     * @return LengthAwarePaginator
     */
    public function searchByNameOrEmail(string $query): LengthAwarePaginator
    {
        return $this->model->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->paginate(15);
    }

    /**
     * Get users with pagination ordered by name
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByNameDesc(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('name', 'asc')->paginate($perPage);
    }

    /**
     * Get users registered in the last X days
     *
     * @param int $days
     * @return Collection
     */
    public function registeredInLastDays(int $days = 30): Collection
    {
        return $this->model->where('created_at', '>=', now()->subDays($days))->get();
    }

    /**
     * ==========================================
     * RAW SQL CRUD METHODS WITH PARAMETER BINDING
     * ==========================================
     */

    /**
     * 1. Raw Select All or Search with Parameter Binding
     *
     * @param string|null $search
     * @return array
     */
    public function rawSelectAll(?string $search): array
    {
        if ($search) {
            return DB::select(
                'SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC',
                ["%{$search}%", "%{$search}%"]
            );
        }

        return DB::select('SELECT * FROM users ORDER BY id DESC');
    }

    /**
     * 2. Raw Select By ID with Parameter Binding
     *
     * @param int $id
     * @return object|null
     */
    public function rawFindById(int $id): ?object
    {
        $users = DB::select('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);

        return !empty($users) ? $users[0] : null;
    }

    /**
     * 3. Raw Insert with Parameter Binding
     *
     * @param array $data
     * @return bool
     */
    public function rawInsert(array $data): bool
    {
        return DB::insert(
            'INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
            [$data['name'], $data['email'], $data['password']]
        );
    }

    /**
     * 4. Raw Update with Parameter Binding
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function rawUpdate(int $id, array $data): int
    {
        if (isset($data['password'])) {
            return DB::update(
                'UPDATE users SET name = ?, email = ?, password = ?, updated_at = NOW() WHERE id = ?',
                [$data['name'], $data['email'], $data['password'], $id]
            );
        }

        return DB::update(
            'UPDATE users SET name = ?, email = ?, updated_at = NOW() WHERE id = ?',
            [$data['name'], $data['email'], $id]
        );
    }

    /**
     * 5. Raw Delete with Parameter Binding
     *
     * @param int $id
     * @return int
     */
    public function rawDelete(int $id): int
    {
        return DB::delete('DELETE FROM users WHERE id = ?', [$id]);
    }
}
