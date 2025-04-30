<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class CompanyDefaultAdminUpdatingRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BasePolicy::check("change-admin-email_company-account");
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            "role_id" => ["required" , "integer" , "exists:roles,id"],
            "user_id" => ["required" , "integer" , "exists:users,id"]
        ];
    }


    // /**
    //  * @return array
    //  */
    // public function messages(): array
    // {
    //     return [

    //     ];
    // }
}
