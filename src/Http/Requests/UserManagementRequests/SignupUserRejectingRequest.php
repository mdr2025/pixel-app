<?php

namespace PixelApp\Http\Requests\UserManagementRequests;
 
use Illuminate\Validation\Rule;
use PixelApp\Models\UsersModule\PixelUser;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class SignupUserRejectingRequest extends BaseFormRequest
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
        $status = request()->input("status");
        return [
            'status' => ["required", "string" , Rule::in(["rejected"])]
        ];
    }
}
