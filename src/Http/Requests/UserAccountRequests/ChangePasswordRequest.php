<?php

namespace PixelApp\Http\Requests\UserAccountRequests;

use ValidatorLib\CustomFormRequest\BaseFormRequest;

class ChangePasswordRequest extends BaseFormRequest
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
                "old_password" =>  ["bail", "required", "string",  "min:8", "max:255"],
                "new_password" =>  ["bail", "required", "string", "confirmed",  "min:8"],
        ];
    }
}
