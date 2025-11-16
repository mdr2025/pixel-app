<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests;

use App\Models\SafetyHubAdminPanel\CompanyModule\Company;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\CompanyModule\TenantCompany;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class ReadCompanyRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('read', TenantCompany::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
