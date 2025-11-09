<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Departments;

use Illuminate\Validation\Rule;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Department;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class DepartmentStoringRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{
    public function authorize()
    {
        return BasePolicy::check('create', Department::class);
    }

    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            'name' => [
                Rule::unique('departments', 'name')
                    ->where(fn($query) => $query->where('branch_id', $data['branch_id'] ?? null))
            ],
        ];
    }

    public function rules()
    {
        return [
            "items"             => ["required", "array", "min:1"],
            "items.*.name"      => ["required", "string", "max:255"] 
        ];
    }
    public function messages()
    {
        return [
            "items.required"        => "Items Array Not Found In The Request Data Bag",
            "items.array"           => "Items Must Be An Array",

            "items.*.name.required" => "Department's Name Has Not Been Sent !",
            "items.*.name.string"   => "Department's Name Must Be String !",
            "items.*.name.max"      => "Department's Name Must Not Be Greater THan 255 Character !",
 
            //single Validation Error Messages
            "name.unique"           => "Department's Name  Is Already Stored In Our Database !"
        ];
    }
}
