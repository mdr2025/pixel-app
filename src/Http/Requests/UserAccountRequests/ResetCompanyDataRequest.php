<?php

namespace PixelApp\Http\Requests\UserAccountRequests;


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
        //dd(BasePolicy::check('resetCompanyData', null));
        return Gate::check('resetCompanyData');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'action' => 'string|max:10|in:DELETE',
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
