<?php

namespace PixelApp\Services\UsersManagement\ControllerLevelServices\User;

use PixelApp\Constants\ViewAsRoleConstants;
use PixelApp\Traits\FilteredBranchesPermissions;

class UserAuthorizationService
{
    use FilteredBranchesPermissions ;

    public function getPermissions(): array
    {
        $loggedUser = auth()->user();
        $userId = $loggedUser->id;
        $roleId = $loggedUser->role_id;

        $isSuperAdmin = $roleId == 1;

        $filteredBranchIds = request()->input('filtered_branches_ids', []);

        $hrPermissions = $this->getFilteredBranchesPermissions(
            $userId,
            $filteredBranchIds,
            [
                'managers' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_HR_MANAGER] ,
                'reps' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_HR_REP]
            ],
            false,
            'HR'
        );

        $itPermissions = $this->getFilteredBranchesPermissions(
            $userId,
            $filteredBranchIds,
            [
                'managers' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_IT_MANAGER] ,
                'reps' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_IT_REP]
            ],
            false,
            'IT'
        );

        $permissions = array_merge($hrPermissions, $itPermissions);

        $permissions['is_super_admin'] = $isSuperAdmin;


        return $permissions;
    }

}
