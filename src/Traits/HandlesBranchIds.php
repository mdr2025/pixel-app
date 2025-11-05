<?php

namespace PixelApp\Traits;

trait HandlesBranchIds
{
    public function getRequestedBranchIdsWithPrimary($userId): array
    {
        $requestedBranchIds = (array) request()->input('filtered_branches_ids', []);
        $primaryBranchId = auth()->user()->primary_branch_id ?? null;

        if ($primaryBranchId && !in_array($primaryBranchId, $requestedBranchIds)) {
            $requestedBranchIds[] = $primaryBranchId;
        }

        return $requestedBranchIds;
    }
}
