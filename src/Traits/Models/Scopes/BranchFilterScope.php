<?php

namespace PixelApp\Traits\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BranchFilterScope
{
    /**
     * Scope a query to only include models related to the accessible branch IDs.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterByAccessibleBranches(Builder $query): Builder
    {
        $user = Auth::user();
        if (!$user || !$user->branch) {
            return $query;
        }

        $primaryBranchId = $user->branch->id;
        $accessibleBranchIds = $user->accessibleBranches()->pluck('id')->toArray();

        $branches = array_merge($accessibleBranchIds, [$primaryBranchId]);

        return $query->whereIn('branch_id', $branches);
    }

    public function scopeFilterByFilteredBranches(Builder $query): Builder
    {
        $user = Auth::user();
        if (!$user || !$user->branch) {
            return $query;
        }

        $primaryBranchId = $user->branch->id;
        $accessibleBranchIds = $user->accessibleBranches()->pluck('id')->toArray();
        $requestedBranchIds = request()->input('filtered_branches_ids', []);

        $requestIsOnlyForPrimaryBranch = count($requestedBranchIds) === 1 && (int) $requestedBranchIds[0] === $primaryBranchId;
        $requestIsEmpty = empty($requestedBranchIds);

        // if the user primary branch is the only requested branch, return the query with the user primary branch ID
        if ($requestIsOnlyForPrimaryBranch || $requestIsEmpty) {
            return $query->where('branch_id', $primaryBranchId);
        }

        // Check for unauthorized branches
        $unauthorizedBranchIds = array_diff($requestedBranchIds, $accessibleBranchIds);

        if (!empty($unauthorizedBranchIds) && !in_array($primaryBranchId, $requestedBranchIds)) {
            return $query->whereRaw('1=0');
        }

        return $query->whereIn('branch_id', $requestedBranchIds);
    }
}
