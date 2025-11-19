<?php

namespace PixelApp\Http\Requests\CompanyAccountRequests;


use Illuminate\Support\Facades\Gate;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class ResetCompanyDataRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    { 
        return Gate::check('resetCompanyData');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string|max:10|in:DELETE',
            'type' => 'required|string|in:partial,full', 
        ];
    }


    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'action' => 'Action "DELETE" must written correctly'
        ];
    }
}
