<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Currencies;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\SystemConfigurationModels\Currency;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UpdatingCurrencyRequest extends BaseFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('edit', Currency::class);
    }


    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "status.boolean" =>  "Currency Status  Must Be Boolean",
            "is_main.boolean" =>  "Currency Main Feature's Status  Must Be Boolean",
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "status" => ["nullable", "boolean"],
            "is_main" => ["nullable", "boolean"],
        ];
    }
}
