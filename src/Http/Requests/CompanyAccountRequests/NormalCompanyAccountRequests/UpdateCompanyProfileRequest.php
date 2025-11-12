<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests\NormalCompanyAccountRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation; 
use PixelApp\Models\PixelModelManager;
use PixelApp\Rules\PhoneNumber;
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
        return BasePolicy::check("edit_company-account");
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
            'cr_no'=> ["nullable" , "string"],
            'email' => ["required" , "string" , "email"],
            'mobile'=> ["required" , PhoneNumber::create()],
            "sector" => ["required" , "string" ],
            "address"          =>['nullable', 'string'],
            "country_id" => ["required" , "integer" ],
            'logo' => ['nullable' , new SingleFileOrSinglePathString()], 
        ];
    }
}
