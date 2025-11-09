<?php

namespace App\Http\Requests\SystemSettings\SystemConfigurations\Branches;

use PixelApp\Models\SystemConfigurationModels\Branch;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchDownloadFileFormateRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return BasePolicy::check('read', Branch::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
