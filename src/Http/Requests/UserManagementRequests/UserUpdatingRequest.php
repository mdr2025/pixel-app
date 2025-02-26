<?php

namespace PixelApp\Http\Requests\UserManagementRequests;
 
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UserUpdatingRequest extends BaseFormRequest
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

  
    public function messages()
    {
        return [
            "status.required" => "Status Has Not Been Sent !",
            "status.in" => "Status Value Is Not Allowed To Be Set !"
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
            "role_id" => ["required", "integer", "exists:roles,id"],
            "department_id" => ["required", "integer", "exists:departments,id"],
        ];
    }
}
