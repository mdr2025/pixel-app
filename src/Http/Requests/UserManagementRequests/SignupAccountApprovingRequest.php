<?php

namespace PixelApp\Http\Requests\UserManagementRequests;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Validation\Rule;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class SignupAccountApprovingRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BasePolicy::check('approveSignUpUsers', PixelUser::class);
    }

    public function messages()
    {
        return [
            "status.required" => "Status has not been sent !",
            "status.in" => "Invalid status value!"
        ];
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function getUserModelPropChangers() : array
    {
        $userModelClass = $this->getUserModelClass();
        return AdminAssignablePropsManager::Singleton()->getSensitivePropChangersForClass($userModelClass);
    }
    
    protected function getModelAssignablePropsRules(array $data = []) : array
    {
        $propChangers = $this->getUserModelPropChangers();
        $propsRules = [];

        /**
         * @var  UserSensitivePropChanger $propChanger
         */
        foreach($propChangers as $propChanger)
        {
            if($propChanger instanceof HasValidationRules)
            {
                $propsRules = array_merge($propsRules ,  $propChanger->getValidationRules($data));
            }
        }

        return $propsRules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    { 
        $rules  = ['status' => ["required", "string" , Rule::in(["active"])] ];
        
        return array_merge($rules , $this->getModelAssignablePropsRules($data));
    }
}
