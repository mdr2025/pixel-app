<?php

namespace PixelApp\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (in_array('branch_id', $model->getFillable())) {
            $branchesIds = request()->query('branches_ids') ?? [];
            $model->whereIn('status', $branchesIds);
        }

    }
}
