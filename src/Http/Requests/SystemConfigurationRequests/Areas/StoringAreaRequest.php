<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Areas;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class StoringAreaRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('create', Area::class);
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["unique:cities,name"],
            "country_id" => ["exists:countries,id"],
            "city_id" => ["exists:cities,id"],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "items" => ["required", "array"],
            "items.*.name" => ["required", "string", "max:255"],
            "items.*.country_id" => ["required", "integer"],
            "items.*.city_id" => ["required", "integer"],
        ];
    }

    public function messages()
    {
        return [
            "items" => "you must submit at least one record",
            "items.*.name" => "Name is required",
            "items.*.name.unique" => "Name is already exists in our database",
            "items.*.country_id" => "Country is required",
            "items.*.country_id.exists" => "Country is not exists in our database",
            "items.*.city_id" => "City is required",
            "items.*.city_id.exists" => "City is not exists in our database",
        ];
    }
}
