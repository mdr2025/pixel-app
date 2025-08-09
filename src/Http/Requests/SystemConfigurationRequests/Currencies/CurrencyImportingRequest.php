<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Currencies;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use PixelApp\Models\SystemConfigurationModels\Currency;
use ValidatorLib\CustomFormRequest\BaseFormRequest;


class CurrencyImportingRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check("create", Currency::class);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "name" => ["unique:currencies,name"],
            "code" => ["nullable", "unique:currencies,code"],
            "symbol"=> ["nullable", "unique:currencies,symbol"],
            "symbol_native"=> ["nullable", "unique:currencies,symbol_native"],
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
            "status" => ["required", "boolean"],
            "code" => ["nullable", "string"],
            "symbol"=> ["nullable", "string"],
            "symbol_native"=> ["nullable", "string"],
            "decimal_digits" => [ "nullable" , "integer"],
            "name_plural" => [ "nullable" , "string"],
            "rounding"=> [ "nullable" , "boolean"],
            "is_main" => [ "nullable" , "boolean"],
        ];
    }
}
