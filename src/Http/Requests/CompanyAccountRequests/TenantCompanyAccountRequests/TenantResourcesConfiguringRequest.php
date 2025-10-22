<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests;

use ValidatorLib\CustomFormRequest\BaseFormRequest;

class TenantResourcesConfiguringRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // because it is called from admin panel side on a fully admin controlled process
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company_domain" => ["required" , "string"]
        ];
    }
}
