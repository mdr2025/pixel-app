<?php

namespace PixelApp\Http\Requests\UserAccountRequests\Signature;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\UsersModule\Signature;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class SignatureStoringRequest extends BaseFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('create', Signature::class);
    }



    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "signature" => ["nullable", "string"],

        ];
    }


}
