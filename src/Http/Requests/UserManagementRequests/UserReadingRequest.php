<?php

namespace PixelApp\Http\Requests\UserManagementRequests;

use App\Models\SystemSettings\UsersModule\User;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Models\UsersModule\PixelUser;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UserReadingRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('readEmployees', PixelUser::class);
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
