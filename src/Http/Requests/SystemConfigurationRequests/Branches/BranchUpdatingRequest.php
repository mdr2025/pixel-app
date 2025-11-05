<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\Branch;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchUpdatingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
        $key = request()->getPathInfo()[-1];
        $rules = [
            "name"      => ["nullable", "string", "max:255"],
            "status"    =>  ["nullable", "boolean"],
        ];
        if ($key != 1 && !request()->has('status')) {
            $rules['parent_id']   = ["required_with:parent_id", "integer", "exists:branches,id"];
            $rules['country_id']  = ["required_with:country_id", "integer", "exists:countries,id"];
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name.unique"           => "Branch Name is already Stored In Our Database !",
            "status"                => "Status must be boolean !",
            "parent_id.required"    => "Parent Branch is required !"
        ];
    }
}
