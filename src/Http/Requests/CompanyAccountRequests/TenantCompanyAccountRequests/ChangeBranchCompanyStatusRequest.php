<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Foundation\Http\FormRequest;

class ChangeBranchCompanyStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // beacause it is checked in tenant side for tenant's db user permissions
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "status" => ['required','string','in:approved,rejected'],
        ];
    }
}
