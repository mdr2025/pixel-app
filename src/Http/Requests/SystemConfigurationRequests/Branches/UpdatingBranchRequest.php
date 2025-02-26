<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\Branch;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UpdatingBranchRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('edit', Branch::class);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["nullable", Rule::unique("branches")->ignore($data["id"])],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => ["nullable", "string", "max:255"],
            "status" =>  ["nullable", "boolean"]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name.unique" => "Branche Name is already Stored In Our Database !",
            "status" => "Status must be boolean !",
        ];
    }
}
