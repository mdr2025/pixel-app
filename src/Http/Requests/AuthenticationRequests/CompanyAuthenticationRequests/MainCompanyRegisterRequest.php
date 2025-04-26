<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;

use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Rules\PhoneNumber;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class MainCompanyRegisterRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        return [
            "domain"             => [ 'unique:tenant_companies,domain'],
            "cr_no"              => [ 'unique:tenant_companies,cr_no'],
            "related_cr_no"      => [ 'exists:tenant_companies,cr_no'],
            "country_id"         => [ 'exists:countries,id'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
            return [
                'name'                       => ['required', 'string'],
                'sector'                     => ['required', 'string'],
                'type'                       => ['required', 'string' , Rule::in(['company' ,'branch'])],
                'cr_no'                      => ['required_if:type,==,company', 'string'],
                'related_cr_no'              => ['required_if:type,==,branch', 'string' , 'exists:tenant_companies,cr_no'],
                'country_id'                 => ['required', 'integer'],
                'logo'                       => ['nullable' , new SingleFileOrSinglePathString()],
                'domain'                     => ['required' , 'string' ],
                "defaultAdmin"               => ["required" , "array" , "min:1"],
                "defaultAdmin.first_name"    => ["required", "string", "max:255"],
                "defaultAdmin.last_name"     => ["required", "string", "max:255"],
                "defaultAdmin.email"         => ["required", "email"],
                "defaultAdmin.mobile"        => ["required",  PhoneNumber::create() ],
                "defaultAdmin.password"      => ["required", "string", "confirmed"],
            ];
    }


    public function messages(){
        return [
            "type.required"              => "Select Your Company type",
            "cr_no.required_if"          => "Please Enter Your CR NO.",
            "related_cr_no.required_if"  => "Please Enter Your Company Related CR NO.",
            "related_cr_no.exists"       => "Not Existed In Our Companies CR NO. Database",
        ];
    }
}
