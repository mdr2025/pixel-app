<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\RoleRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class RoleSwitchingRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BasePolicy::check("edit" , RoleModel::class);
    }

    public function messages()
    {
        return [
            "status.required" => "Role Status Has Not Been Sent",
            "status.boolean" => "A Role Status Value Must Be 1 Or 0"
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    {
            return [
                'status' => ['required' , 'boolean'],
            ];

    }
}
