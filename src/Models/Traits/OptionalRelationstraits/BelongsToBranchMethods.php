<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToBranchMethods
{ 

    protected function getBranchModelClass(): string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function branch() : BelongsTo
    {
        return $this->belongsTo(  $this->getBranchModelClass() , "branch_id" );
    }

    /**
     * Get the branches the user can access
     */
    public function accessibleBranches(): BelongsToMany
    {
        return $this->belongsToMany(
                    $this->getBranchModelClass(),
                    'accessible_branch_user',
                    'user_id',
                    'branch_id'
               );
    }

    public function getBranchForeignKeyName() : string
    {
        return 'branch_id';
    }
    
    /**
     * Get the user's primary branch ID
     */
    public function getPrimaryBranchIdAttribute(): ?int
    {
        return $this->branch_id;
    }

    public function getAccessibleBranchesTableName() : string
    {
        return "accessible_branch_user";
    }
    
    public function getBranchPropChanger() : UserSensitivePropChanger
    {
        return new BranchChanger();
    }

    protected function appendBranchFields() : void
    {
        $this->fillable['branch_id'] = 'branch_id';
        $this->casts['branch_id'] = 'integer';
    }
}