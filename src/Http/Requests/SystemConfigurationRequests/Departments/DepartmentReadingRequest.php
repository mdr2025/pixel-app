<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;

use PixelApp\Models\SystemConfigurationModels\Department;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class DepartmentReadingRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return BasePolicy::check('read', Department::class);
    }

    public function rules(): array
    {
        return [];
    }
}
