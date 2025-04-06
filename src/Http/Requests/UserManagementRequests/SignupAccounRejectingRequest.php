<?php

namespace PixelApp\Http\Requests\UserManagementRequests;
 
use Illuminate\Validation\Rule; 
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class SignupAccounRejectingRequest extends BaseFormRequest
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
            "status.required" => "Status has not been sent !",
            "status.in" => "Invalid status value!"
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
            'status' => ["required", "string" , Rule::in(["rejected"])]
        ];
    }
}
