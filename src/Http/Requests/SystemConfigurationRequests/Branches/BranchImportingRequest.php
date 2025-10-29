<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Branch;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchImportingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('create', Branch::class);
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["unique:branches,name"],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [ 
            "id" => ["nullable" , "integer"],
            "name" => ["required", "string", "max:255"],
            "status" => ["nullable", "boolean"],
            "default" => ["required" , "boolean"]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name" => "Name is required",
            "name.unique" => "Name is already exists in our database",
            "status.boolean" => "Status must be boolean",
        ];
    }
}
