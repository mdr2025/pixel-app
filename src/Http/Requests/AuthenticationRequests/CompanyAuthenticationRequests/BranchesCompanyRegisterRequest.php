<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;

use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class BranchesCompanyRegisterRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            "domain" => [ 'unique:tenant_companies,domain'],
            "country_id" => [ 'exists:countries,id'],
        ];
    }

    /**
     * Get the validation rules that PixelApply to the request.
     *
     * @return array
     */
    public function rules()
    {
            return [
                'name' => ['required', 'string'],
                'sector' => ['required', 'string'],
                'country_id' => ['required', 'integer'],
                'logo' => ['nullable' , new SingleFileOrSinglePathString()],
                'domain' => ['required' , 'string' ],
                "defaultAdmin" => [ "required" , "array" , "min:1"],
                "defaultAdmin.first_name" => ["required", "string", "max:255"],
                "defaultAdmin.last_name" => ["required", "string", "max:255"],
                "defaultAdmin.email" => ["required", "email"],
                "defaultAdmin.mobile" => ["required", "string" , "max:20" ],
                "defaultAdmin.password" => ["required", "string", "confirmed"],
            ];
    }

 }
