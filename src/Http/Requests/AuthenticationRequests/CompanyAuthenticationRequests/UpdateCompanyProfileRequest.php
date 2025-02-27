<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;
 
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\CompanyModule\TenantCompany;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class UpdateCompanyProfileRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            "name" => Rule::unique( TenantCompany::getTableName() )->ignore($data["id"]),
            "country_id" => [ 'exists:countries,id']
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required" , "string" ],
            "country_id" => ["required" , "integer" ],
            'logo' => ['nullable' , new SingleFileOrSinglePathString()],
            "company_domain" => ["required" , "string"] ,
            "employees_no"=>"required|string",
        ];
    }
}
