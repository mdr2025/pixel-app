<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Rules\NotMainBranchName;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchStoringRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            'name' => ['unique:branches,name'],
            'parent_id' => ['exists:branches,id'],
            'country_id' => ['exists:countries,id'],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255', new NotMainBranchName()],
            'items.*.parent_id' => ['required', 'integer'],
            'items.*.country_id' => ['required', 'integer'],
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
            "name.unique" => "Name is already exists in our database", 
            "items.*.parent_id.required" => "Parent Branch is required !"
        ];
    }
}
