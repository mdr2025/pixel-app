<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\Department;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UpdatingDepartmentRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check("edit", Department::class);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["nullable", Rule::unique("departments", "name")->ignore($data["id"])],
        ];
    }

    public function rules(): array
    {
        return [
            "name" => ["nullable", "string"],
            "status" => ["nullable", "boolean"],
        ];
    }
}
