<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;

use PixelApp\Models\SystemConfigurationModels\Branch;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchReadingRequest extends BaseFormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('read', Branch::class);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
