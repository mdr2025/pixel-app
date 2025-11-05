<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class BranchChanger 
     extends UserSensitivePropChanger
     implements ExpectsSensitiveRequestData , HasValidationRules
{
    use ExpectsSensitiveRequestDataFunc;

    protected ?Branch $branch = null;

    /**
     * @param Branch|null $branch
     * @return $this
     * @throws Exception
     */
    public function setBranch(?Branch $branch): self
    {
        if(!$branch->isActive())
        {
            throw new Exception("The provided branch is not active");
        }
        $this->branch = $branch;
        return $this;
    }
    public function getPropName() : string
    {
        return 'branch_id';
    }
    public function getPropRequestKeyDefaultName(): string
    {
        return 'branch_id';
    }

    public function getValidationRules(array $data = []) : array
    {
        return [
                    $this->getPropName()    => [ "required"  , "integer", "exists:branches,id"],
                    'accessible_branches'   => ['nullable', 'array'],
                    'accessible_branches.*' => ['integer', 'exists:branches,id'],
               ];
    }

    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        $value = $this->branch?->id ?? $this->getPropNewRequestValue();
        return $this->composeChangesArray( $value );
    }
}
