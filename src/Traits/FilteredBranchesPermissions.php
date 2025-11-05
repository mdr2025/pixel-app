<?php

namespace PixelApp\Traits;

use PixelApp\Constants\ViewAsRoleConstants;

trait FilteredBranchesPermissions
{
    use HqRoleChecker, BranchRoleChecker;

    private static int $mainBranchId  = 1; // static value for main branch id

    public function getFilteredBranchesPermissions(int $userId, array $filteredBranchIds = [], array $customRoleSettings = [], bool $useDefaultSettings = true, string $roleCheckerDepartment = 'HSE'): array
    {
        $permissions = [];

        $primaryBranchId = auth()->user()->branch_id;

        // add primary branch id to filtered branch ids if it's not in the array
        if ($primaryBranchId && !in_array($primaryBranchId, $filteredBranchIds)) {
            $filteredBranchIds[] = $primaryBranchId;
        }

        /**
         * default settings for departments roles
         * enabled: true if the role is enabled => it will appear in the permissions array ( true || false )
         * value: the value of the role => it will be used to check if the user has the role
         */
        $defaultSettings = [
            'managers' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_HSE_MANAGER],
            'engineers' => ['enabled' => true, 'value' => ViewAsRoleConstants::BRANCH_HSE_ENGINEER],
        ];

        // merge default settings with custom settings if useDefaultSettings is true
        $mergedSettings = $useDefaultSettings ? array_merge($defaultSettings, $customRoleSettings) : $customRoleSettings;

        // check if main branch id is in the filtered branch ids
        if (in_array(self::$mainBranchId, $filteredBranchIds)) {

            foreach ($mergedSettings as $role => $setting) {
                // check if the role is enabled
                $enabled = $setting['enabled'] ?? false;
                if ($enabled === true) {
                    $roleValue = $setting['value'];

                    $permissions["is_hq_{$roleValue}"] = $this->hqRoleChecker([$role], $roleCheckerDepartment);
                }
            }
        }

        // loop through filtered branch ids and check if the role is enabled
        foreach (array_diff($filteredBranchIds, [self::$mainBranchId]) as $branchId) {
            foreach ($mergedSettings as $role => $setting) {
                $enabled = $setting['enabled'] ?? false;
                if ($enabled === true) {
                    $roleValue = $setting['value'];

                    $permissions["is_{$branchId}_{$roleValue}"] = $this->branchRoleChecker($branchId, [$role], $roleCheckerDepartment);
                }
            }
        }

        return $permissions;
    }
}
