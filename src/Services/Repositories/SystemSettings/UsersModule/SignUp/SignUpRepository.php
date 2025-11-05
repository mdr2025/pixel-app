<?php

namespace PixelApp\Services\Repositories\SystemSettings\UsersModule\SignUp;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use Spatie\QueryBuilder\QueryBuilder;

class SignUpRepository
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
    public function getSignUpList(array $filters = [], array $relations = [], array $branchIds = [])
    {
        return $this->initUserSpatieQueryBuilder()
                    ->with([...$relations])
                    ->allowedFilters([...$filters])
                    ->allowedSignUps()
                    ->datesFiltering()
                    ->customOrdering()
                    ->whereIn('branch_id', $branchIds)
                    ->paginate((int) request()->pageSize ?? 10);
    }

    public function fetchByIdOrFail(int $userId) : PixelUser
    {
        $userModelClass = $this->getUserModelClass();
        return $userModelClass::findOrFail($userId);
    }
}
