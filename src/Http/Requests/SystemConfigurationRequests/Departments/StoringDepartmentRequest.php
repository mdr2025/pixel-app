<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Department;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class StoringDepartmentRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check("create", Department::class);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["unique:departments,name"],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "items" => ["required", "array"],
            "items.*.name" => ["required", "string"],
            "items.*.status" => ["nullable", "boolean"],
        ];
    }
}
