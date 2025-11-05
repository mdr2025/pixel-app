<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;

use PixelApp\Models\SystemConfigurationModels\Department;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class DepartmentSupervisorsRequest extends BaseFormRequest
{
    public function authorize()
    {
        return BasePolicy::check('hasDepartmentAccess', Department::class);
    }

    public function messages()
    {
        return [
            'department_rep_ids.*.exists' => 'One or more selected users do not exist!',
        ];
    }


    public function rules()
    {
        return [
            'department_rep_ids.*' => ['exists:users,id'],
        ];
    }
}
