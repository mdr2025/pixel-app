<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\Department;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class DepartmentImportingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            "branch_id" => ["exists:branches,id"],
            "name" => [ 
                        Rule::unique('departments', 'name')
                            ->where(fn($query) => $query->where('branch_id', $data['branch_id'] ?? null))
                      ]
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "id" => ["nullable" , "integer"],
            "name" => ["required", "string"],
            "status" => ["nullable", "boolean"],
            "is_default" => ["nullable" , "boolean"],
            "branch_id" => ["required" , "integer"]
        ];
    }
}
