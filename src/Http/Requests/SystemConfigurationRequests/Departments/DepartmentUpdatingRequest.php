<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\Department;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class DepartmentUpdatingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{
    public function authorize()
    {
        return BasePolicy::check('edit', Department::class);
    }

    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            'name' => [
                Rule::unique('departments', 'name')
                    ->ignore($data['id'] ?? null) // Ignore current record when updating
                    ->where(
                        fn($query) =>
                        $query->where('branch_id', $data['branch_id'] ?? null)
                    ),
            ],
        ];
    }

    public function rules($data)
    {
        return [
            "name"      => ["required_if:name,==,null", "string", "max:255"],
            "status"    => ["nullable", "boolean"]
        ];
    }

    public function messages()
    {
        return [
            "name.string"    => "Department's Name Must Be String !",
            "name.max"       => "Department's Name Must Not Be Greater THan 255 Character !",
            "name.unique"    => "Department's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Department's Status  Must Be Boolean",
        ];
    }
}
