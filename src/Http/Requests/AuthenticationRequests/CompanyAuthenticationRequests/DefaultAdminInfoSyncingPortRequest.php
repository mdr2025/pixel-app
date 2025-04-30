<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;

use PixelApp\Rules\PhoneNumber;
use ValidatorLib\CustomFormRequest\BaseFormRequest; 

class DefaultAdminInfoSyncingPortRequest extends BaseFormRequest 
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /**
         * @todo later :
         * there is no guard or logged user to check its permissions
         * must find an other way to protected the apis
         */
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
                "company_domain" => ["required" , "string" , "exists:tenant_companies,domain"],
                "first_name"    => ["required", "string", "max:255"],
                "last_name"     => ["required", "string", "max:255"],
                'name'          => ["required" , "string"],
                "email"         => ["required", "email"],
                "mobile"        => ["required",  PhoneNumber::create() ],
                "password"      => ["required", "string"],
                'verification_token' => ["nullable" , "string"],
                'email_verified_at' => ["nullable" , "date"],
                'updated_at' => ["nullable" , "date"]
            ];
    }


    // public function messages(){
    //     return [
           
    //     ];
    // }
}
