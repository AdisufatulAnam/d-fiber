<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\DTOs\UserDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    protected UserRepository $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated users or search results (Eloquent)
     *
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function getUsersList(?string $search): LengthAwarePaginator
    {
        if ($search) {
            return $this->userRepository->searchByNameOrEmail($search);
        }

        return $this->userRepository->getByNameDesc();
    }

    /**
     * ==========================================
     * RAW SQL CRUD METHODS (Delegated to Repository)
     * ==========================================
     */

    /**
     * 1. Read All / Search using Raw SQL Repository
     *
     * @param string|null $search
     * @return array
     */
    public function getRawUsersList(?string $search): array
    {
        return $this->userRepository->rawSelectAll($search);
    }

    /**
     * 2. Read Single User by ID using Raw SQL Repository
     *
     * @param int $id
     * @return object|null
     */
    public function getRawUserById(int $id): ?object
    {
        return $this->userRepository->rawFindById($id);
    }

    /**
     * 3. Create User using Raw SQL Repository & Transaction
     *
     * @param UserDTO $dto
     * @return bool
     * @throws Exception
     */
    public function createRawUser(UserDTO $dto): bool
    {
        DB::beginTransaction();

        try {
            $data = [
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ];

            $result = $this->userRepository->rawInsert($data);

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create raw user: ' . $e->getMessage());
            throw new Exception('Could not create user via raw SQL.');
        }
    }

    /**
     * 4. Update User using Raw SQL Repository & Transaction
     *
     * @param int $id
     * @param array $data
     * @return int
     * @throws Exception
     */
    public function updateRawUser(int $id, array $data): int
    {
        DB::beginTransaction();

        try {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $affected = $this->userRepository->rawUpdate($id, $data);

            DB::commit();

            return $affected;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update raw user ID ' . $id . ': ' . $e->getMessage());
            throw new Exception('Could not update user via raw SQL.');
        }
    }

    /**
     * 5. Delete User using Raw SQL Repository & Transaction
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function deleteRawUser(int $id): int
    {
        DB::beginTransaction();

        try {
            $deleted = $this->userRepository->rawDelete($id);

            DB::commit();

            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete raw user ID ' . $id . ': ' . $e->getMessage());
            throw new Exception('Could not delete user via raw SQL.');
        }
    }
}
