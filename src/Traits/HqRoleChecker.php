<?php

namespace PixelApp\Traits;

use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\PixelModelManager;

trait HqRoleChecker
{
    public function hqRoleChecker(array $relations , string $departmentName = 'HSE'): bool
    {
        $mainBranch = $this->getMainBranch();

        if (!$mainBranch) {
            return false;
        }

        foreach ($relations as $relation) {
            if ($mainBranch->departments()->where('name', $departmentName)->whereHas($relation, fn($q) => $q->where('id', auth()->id()))->exists()) {
                return true;
            }
        }

        return false;
    }
    
    private function getMainBranch(): Branch
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class)::find(1);
    }
}
