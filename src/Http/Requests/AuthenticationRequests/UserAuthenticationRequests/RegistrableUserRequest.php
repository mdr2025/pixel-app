<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;

use Illuminate\Validation\Rule;
use PixelApp\Rules\PhoneNumber;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class RegistrableUserRequest extends BaseFormRequest
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
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            'email' => ["required", "email" ,"unique:users,email"],
            'mobile' => ["required",  PhoneNumber::create() , "unique:users,mobile"],
            "password" => ["required", "string", "confirmed"],
            "profile" => [ "nullable" , "array" ],
            "profile.*.country_id" => [ "nullable" , "numeric"  , "exists:countries,id"],
            "profile.*.picture" => [ "nullable" ,  (new SingleFileOrSinglePathString()) ],
            "profile.*.gender" => [ "nullable" , "string" , Rule::in(['male', 'female'])],

        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            "email.unique" => "Chose another email for registering in our system",
        ];
    }
}
