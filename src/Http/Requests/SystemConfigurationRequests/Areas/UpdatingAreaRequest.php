<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Areas;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UpdatingAreaRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('edit', Area::class);
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["nullable", Rule::unique("areas")->ignore($data["id"], "id")],
            "country_id" => ["nullable", "exists:countries,id"],
            "city_id" => ["nullable", "exists:cities,id"],
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
            "city_id" =>  ["nullable", "integer"],
            "status" =>  ["nullable", "boolean"]
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
            "city_id.exists" => "Country is not exists in our database",
        ];
    }
}
