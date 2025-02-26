<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;

use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsRelationshipsKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class RegisterRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation , NeedsRelationshipsKeyAdvancedValidation
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

    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "email" => ["unique:users,email"],
            "mobile" => ["unique:users,mobile"]
        ];
    }

    public function getProfileAdvancedValidationRules(array $data = []): array
    {
        return [
            "country_id" => [ "nullable" ,"exists:countries,id"]
        ];
    }

    public function getRelationshipKeyAdvancedValidationRules(string $relationshipName, array $data = []): array
    {
        return match($relationshipName){

            'profile'    => $this->getProfileAdvancedValidationRules($data),
            default => []

        } ;
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
            'email' => ["required", "email"],
            'mobile' => ["required", "string" , "max:20" ],
            "password" => ["required", "string", "confirmed"],
            "profile" => [ "nullable" , "array" ],
            "profile.country_id" => [ "nullable" , "numeric"  ],
            "profile.logo" => [ "nullable" ,  (new SingleFileOrSinglePathString()) ],
            "profile.gender" => [ "nullable" , "string" , Rule::in(['male', 'female'])],

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
            "email.unique" => "This mail already exists in database Please try another one",
        ];
    }
}
