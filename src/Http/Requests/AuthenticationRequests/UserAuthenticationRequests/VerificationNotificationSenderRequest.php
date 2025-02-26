<?php

namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;


use ValidatorLib\CustomFormRequest\BaseFormRequest;

class VerificationNotificationSenderRequest extends BaseFormRequest
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
            "email.required" => "Email Has Not Been Sent !",
            "email.email" => "Please Enter A Valid Email !",
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
            'email' => [ "required", "string", "email"]
        ];

    }
}
