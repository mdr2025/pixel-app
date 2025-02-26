<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;


use ValidatorLib\CustomFormRequest\BaseFormRequest;

class VerificationTokenRequest extends BaseFormRequest
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
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            "token.required" => "Token Has Not Been Sent"
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
            "token" => [  "required", "string"]
        ];
    }
}
