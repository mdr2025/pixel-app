<?php

namespace PixelApp\Http\Requests\UserManagementRequests;

use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class UserUpdatingRequest extends BaseFormRequest
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
 
      
    public function rules($data): array
    {

        return $this->getModelAssignablePropsRules($data); 
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
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'role_id.required'              => 'Role has not been sent !',
            'role_id.integer'               => 'Role format is invalid!',
            'role_id.exists'                => 'Role does not exist!',
            'department_id.required'        => 'Department has not been sent !',
            'department_id.integer'         => 'Department format is invalid!',
            'department_id.exists'          => 'Department does not exist!',
            'branch_id.required'            => 'Branch has not been sent !',
            'branch_id.integer'             => 'Branch format is invalid!',
            'branch_id.exists'              => 'Branch does not exist!',
            'accessible_branches.array'     => 'Accessible branch format is invalid!',
            'accessible_branches.*.integer' => 'Accessible branch format is invalid!',
            'accessible_branches.*.exists'  => 'Accessible branch does not exist!'
        ];
    }
}
