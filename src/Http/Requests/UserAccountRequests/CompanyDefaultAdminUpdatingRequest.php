<?php

namespace PixelApp\Http\Requests\UserAccountRequests;


use Illuminate\Support\Facades\Gate;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use PixelApp\Models\UsersModule\PixelUser;

class CompanyDefaultAdminUpdatingRequest extends BaseFormRequest
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
