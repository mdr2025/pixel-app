<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations;

use PixelApp\Models\Traits\OptionalRelationsTraits\BelongsToDepartmentMethods;
use PixelApp\Models\SystemConfigurationModels\Branch;
use App\Models\SystemSettings\SystemConfigurationModels\StandardCommitteeMember;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\SystemSettings\UsersModule\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToDepartment;
use PixelApp\Models\ModelConfigs\DropdownLists\BranchesOperations\BranchConfig;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\BranchRepositoryInterfaces\BranchRepositoryInterface;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers\BranchesGetter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers\BranchesListter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers\BranchesTeamsGetter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers\FirstParentBranchGetter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers\SubBranchesListter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchService;
use Reflection;

class BranchRepository implements BranchRepositoryInterface
{
    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType( Branch::class );
    }

    protected function initBranchSpatieQueryBuilder() :  QueryBuilder
    {
        $branchModel = $this->getBranchModelClass();

        return QueryBuilder::for(  $this->getBranchModelClass()  );
    }

    protected function initBranchesGetter() : QueryCustomizer
    {
        return BranchesGetter::create();
    }

    public function getBranches()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initBranchesGetter()->customizeQuery($query)->getResult();
    }

    protected function initFirstParentBranchGetter() : QueryCustomizer
    {
        return FirstParentBranchGetter::create();
    }

    public function getFirstParentBranch()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initFirstParentBranchGetter()->customizeQuery($query)->getResult();
    }

    protected function initBranchesTeamsGetter() : QueryCustomizer
    {
        return BranchesTeamsGetter::create();
    }

    public function getBranchesTeams()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initBranchesTeamsGetter()->customizeQuery($query)->getResult();
    }

    public function getCountActiveBranches()
    {
        return $this->getBranchModelClass()::active()->count();
    }

    protected function initBranchesListter() : QueryCustomizer
    {
        return BranchesListter::create();
    }

    public function getListBranches()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initBranchesListter()->customizeQuery($query)->getResult();
    }

    protected function initSubBranchesListter() : QueryCustomizer
    {
        return SubBranchesListter::create();
    }

    public function getSubBranches()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initSubBranchesListter()->customizeQuery($query)->getResult();
    }

    
    public function fetchBranchById(int $id) : ?Branch
    {
        return $this->getBranchModelClass()::find($id);
    }
    
    public function fetchBranchByIdOrFail(int $id) : Branch
    {
        return $this->getBranchModelClass()::findOrFail($id);
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function doesUserUseBelongsToDepartmentMethods(string $userModelClass) : bool
    {
        $interfaces = class_uses($userModelClass);
        return in_array(BelongsToDepartmentMethods::class, $interfaces);
    }

    protected function updateUserOnDepartmentCondition(int $departmentId) : bool
    {
        $userModelClass = $this->getUserModelClass();
        return $userModelClass::where('department_id', $departmentId)->update(['dep_role' => null]);
    }

    protected function getRequestKeysRoleTypesMap() : array
    {
        return [
            'managers_ids' => PixelUser::DEP_TYPE_MANAGER,
            'reps_ids' => PixelUser::DEP_TYPE_REP,
        ];
    }
    
    public function addMembersToDepartment()
    {
        $userModelClass = $this->getUserModelClass();

        if(! $this->doesUserUseBelongsToDepartmentMethods($userModelClass) )
        {
            return Response::error("The user module doesn't support Department Functinality !");
        }

        $request = request();
        $departmentId = $request->department_id;

        // Reset dep_role for all users in the department
        $this->updateUserOnDepartmentCondition($departmentId);

        // Define mapping between request keys and role types
        $roles = $this->getRequestKeysRoleTypesMap();

        // Loop through each roles and assign it
        foreach ($roles as $key => $role)
        {
            $this->updateDepartmentUsers($request, $departmentId, $key, $role);
        }

        return Response::success([], ['Members assigned successfully']);
    }

    private function updateDepartmentUsers(Request $request, int $departmentId, string $key, string $role)
    {
        if (!empty($request->$key))
        {
            $userModelClass = $this->getUserModelClass();

            $userModelClass::where('department_id', $departmentId)
                            ->whereIn('id', $request->input($key))
                            ->allowedUsers()
                            ->update(['dep_role' => $role]);
        }
    }
}
