<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;

use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Rules\PhoneNumber;
use ValidatorLib\CustomFormRequest\BaseFormRequest; 

class DefaultAdminInfoUpdatingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
                "mobile"        => ["required",  PhoneNumber::create() ],
                "password"      => ["required", "string", "confirmed"],
                "country_id" => ["required" , "integer"]
            ];
    }
    
    public function getModelKeyAdvancedValidationRules(array $data = []) : array
    {
        return [
            "country_id" => ["exists:countries,id"]
        ];
    }

    // public function messages(){
    //     return [
           
    //     ];
    // }
}
