<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;

use ValidatorLib\CustomFormRequest\BaseFormRequest;

class TokenRefreshingRequest extends BaseFormRequest
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


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "refresh_token" => [ "required", "string" , "size:80"],
        ];
    }
}
