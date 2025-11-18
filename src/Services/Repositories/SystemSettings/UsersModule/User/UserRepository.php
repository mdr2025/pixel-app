<?php

namespace PixelApp\Services\Repositories\SystemSettings\UsersModule\User;

use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use Spatie\QueryBuilder\QueryBuilder;


class UserRepository
{

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function initUserSpatieQueryBuilder() : QueryBuilder
    {
        $userModelClass = $this->getUserModelClass();
        return QueryBuilder::for($userModelClass);
    }    
    public function getUsers(array $filters = [], array $relations = [])
    {
        return $this->initUserSpatieQueryBuilder()
                    ->filterByFilteredBranches()
                    ->with([...$relations])
                    ->allowedFilters([...$filters])
                    ->datesFiltering()
                    ->allowedUsers()
                    ->customOrdering('accepted_at', 'desc')
                    ->paginate((int) request()->pageSize ?? 10);
    }

    public function getUsersList(array $filters = [], array $relations = [])
    {
        return $this->initUserSpatieQueryBuilder()
                    ->allowedFilters([...$filters])
                    ->with([...$relations])
                    ->allowedUsers()
                    ->customOrdering('created_at', 'desc')
                    ->get();
    }

    public function getTotalActiveUsers()
    {
        return $this->getUserModelClass()::allowedUsers()->count();
    }

    public function filterUsersByBranch(array $filters = [], array $relations = [])
    {
        return $this->initUserSpatieQueryBuilder()
                    ->allowedFilters([
                        ...$filters
                    ])
                    ->with([...$relations])
                    ->allowedUsers()
                    ->customOrdering('created_at', 'desc')
                    ->get();
    }

    public function getPrimaryBranchFromUser($user)
    {
        return $user->branch()->select('id', 'name')->first();
    }

    public function getAccessibleBranchesIdsFromUser($user)
    {
        return $user->accessibleBranches()->pluck('id')->toArray();
    }

    public function getAccessibleBranchesFromUser($user)
    {
        return $user->accessibleBranches()->select('id', 'name')->get();
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function getBranchesByIds(array $branchIds)
    {
        return $this->getBranchModelClass()::whereIn('id', $branchIds)
                    ->select('id', 'name')
                    ->get()
                    ->toArray();
    }

    public function listDefaultUsers(array $filters = [])
    {
        return $this->initUserSpatieQueryBuilder()
                    ->allowedFilters([...$filters])
                    ->with(['profile:user_id,picture'])
                    ->withoutSuperAdmin()
                    ->allowedUsers()
                    ->customOrdering('created_at', 'desc')
                    ->select('id', 'name', 'email', 'hashed_id')
                    ->get();
    }

    public function updateUser(PixelUser $user, array $data)
    {
        $user->update($data);

        return $user;
    }

    public function findUserById($id)
    {
        return $this->getUserModelClass()::find($id);
    }
    
    public function findUserByIdOrFail($id)
    {
        return $this->getUserModelClass()::findOrFail($id);
    }
    
    /**
     * @todo must use RoleChanger
     */
    public function updateRoleByEmail(string $email, int $currentRoleId, int $newRoleId)
    {
        return $this->getUserModelClass()::where('email', $email)
                    ->where('role_id', $currentRoleId)
                    ->update(['role_id' => $newRoleId]);
    }

    public function syncAccessibleBranches(PixelUser $user, array $branchIds)
    {
        $user->accessibleBranches()->sync($branchIds);
    }
}
