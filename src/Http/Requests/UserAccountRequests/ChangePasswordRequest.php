<?php

namespace PixelApp\Http\Requests\UserAccountRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\UsersModule\UserProfile;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class ChangePasswordRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BasePolicy::check("edit_profile" , UserProfile::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                "old_password" =>  [ "required", "string",  "min:8", "max:255"],
                "new_password" =>  [ "required", "string", "confirmed",  "min:8"],
        ];
    }
}
