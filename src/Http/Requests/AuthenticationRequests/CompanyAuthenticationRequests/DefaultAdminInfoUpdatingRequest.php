<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;
 
use ValidatorLib\CustomFormRequest\BaseFormRequest; 

class DefaultAdminInfoUpdatingRequest extends BaseFormRequest 
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
                "company_domain" => ["required" , "string"],
                "first_name"    => ["required", "string", "max:255"],
                "last_name"     => ["required", "string", "max:255"],
                "email"         => ["required", "email"],
                "mobile"        => ["required", "string" , "max:20" ],
                "password"      => ["required", "string", "confirmed"],
            ];
    }


    // public function messages(){
    //     return [
           
    //     ];
    // }
}
