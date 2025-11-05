<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\RoleRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class RoleStoringRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BasePolicy::check('create' , RoleModel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1']
        ];
    }
}
