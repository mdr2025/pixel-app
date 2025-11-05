<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;

use PixelApp\Models\SystemConfigurationModels\Department;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class DepartmentDownloadFileFormateRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return BasePolicy::check('create', Department::class);
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
