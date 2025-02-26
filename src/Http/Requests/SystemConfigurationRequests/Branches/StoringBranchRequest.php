<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Branch;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class StoringBranchRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            "items" => ["required", "array", "min:1"],
            "items.*.name" => ["required", "string", "max:255"],
            "items.*.status" => ["required", "boolean"],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "items" => "Branches are required, you must submit at least one record",
            "items.*.name" => "Name is required",
            "items.*.name.unique" => "Name is already exists in our database",
            "items.*.status" => "Status is required",
            "items.*.status.boolean" => "Status must be boolean",
        ];
    }
}
