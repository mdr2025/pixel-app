<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\RoleRequests;

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
        return true;
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
            'permissions' => ['nullable', 'array', 'min:1']
        ];
    }
}
