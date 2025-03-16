<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Cities;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class CityImportingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
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
            "name" => ["unique:cities,name"],
            "country_id" => ["exists:countries,id"],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "id" => ["nullable" , "integer"],
            "name" => ["required", "string", "max:255"],
            "country_id" => ["required", "integer"],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name" => "Name is required",
            "name.unique" => "Name is already exists in our database",
            "country_id" => "Country is required",
            "country_id.exists" => "Country is not exists in our database",
        ];
    }
}
