<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Cities;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UpdatingCitiesRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check("create", City::class);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => [Rule::unique("cities")->ignore($data["id"], "id")],
            "country_id" => ["exists:countries,id"],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => ["nullable", "string", "max:255"],
            "country_id" =>  ["nullable", "integer"],
            "status" =>  ["nullable", "boolean"],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name.unique" => "City Name is already Stored In Our Database !",
            "country_id.exists" => "Country is not exists in our database",
        ];
    }
}
