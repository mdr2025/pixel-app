<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests;

use Illuminate\Foundation\Http\FormRequest;

class TenantResourcesConfiguringCancelingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // because it is called from tenant app central domain side on a fully system controlled process
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company_domain" => ["required" , "string"],
            "messeage" => ["nullable" , "string"],
            "code" => ["nullable" , "integer"]
        ];
    }
}
