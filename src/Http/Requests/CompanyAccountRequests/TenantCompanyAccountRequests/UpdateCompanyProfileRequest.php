<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule; 
use PixelApp\Models\PixelModelManager;
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
        return true; // beacause it is checked in tenant side for tenant's db user permissions
    }

    protected function getTenantModelClass() : string
    {
        return PixelModelManager::getBaseTenantCompanyModelClass();
    }

    protected function getTenantCompaniesTableName() : string
    {
        return $this->getTenantModelClass()::getTableName();
    }

    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => Rule::unique( $this->getTenantCompaniesTableName() )->ignore($data["id"]),
            "cr_no" => Rule::unique( $this->getTenantCompaniesTableName() )->ignore($data["id"]),
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
            "address"          =>['nullable', 'string'],
            "country_id" => ["required" , "integer" ],
            'logo' => ['nullable' , new SingleFileOrSinglePathString()],
            'cr_no'=> ["nullable" , "string"],
            "company_domain" => ["required" , "string"] ,
            // "employees_no"=>"required|string",
        ];
    }
}
