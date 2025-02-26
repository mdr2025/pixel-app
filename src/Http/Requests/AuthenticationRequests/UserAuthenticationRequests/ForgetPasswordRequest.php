<?php


namespace PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests;

use ValidatorLib\CustomFormRequest\BaseFormRequest;

class ForgetPasswordRequest extends BaseFormRequest
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

    public function messages()
    {
        return [
            "email.required" => "Email Has Not Been Sent",
            "email.email" => "The Given Email Is Invalid",
            "email.exists" => "The Given Email Is Not Stored In Our Databases"
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
            'email' => [ "required", "string", "email", "exists:users,email"]
        ];
    }
}
