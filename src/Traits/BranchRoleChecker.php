<?php

namespace PixelApp\Traits;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

trait BranchRoleChecker

{
    public function branchRoleChecker(?int $branchId, array $relations , string $departmentName = 'HSE'): bool
    {
        $branch = $this->getBranchById($branchId);

        if (!$branch) {
            return false;
        }

        foreach ($relations as $relation) {
            if ($branch->departments()->where('name', $departmentName)->whereHas($relation, fn($q) => $q->where('id', auth()->id()))->exists()) {
                return true;
            }
        }

        return false;
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    private function getBranchById(?int $branchId): ?Branch
    {
        if (!$branchId) {
            return null;
        }

        return $this->getBranchModelClass()::find($branchId);
    }
}
//Commented out for now