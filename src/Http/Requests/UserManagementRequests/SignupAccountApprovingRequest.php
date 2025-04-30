<?php

namespace PixelApp\Http\Requests\UserManagementRequests;
 
use Illuminate\Validation\Rule;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class SignupAccountApprovingRequest extends BaseFormRequest
{
    // protected static bool $mustCheckRoleId = false;
    // protected static bool $mustCheckDepartmentId = false;
    // protected static bool $mustCheckBranchId = false;

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
            "status.required" => "Status has not been sent !",
            "status.in" => "Invalid status value!"
        ];
    }
    // public static function mustCheckRoleId() : void
    // {
    //     static::$mustCheckRoleId = true;
    // }

    // protected function getRoleIdRequirmentStatus() : string
    // {
    //     return static::$mustCheckRoleId ? "required" : "nullable";
    // }

    // public static function mustCheckDepartmentId() : void
    // {
    //     static::$mustCheckDepartmentId = true;
    // }
    // protected function getDepartmentIdRequirmentStatus() : string
    // {
    //     return static::$mustCheckDepartmentId ? "required" : "nullable";
    // }
    
    // public static function mustCheckBranchId() : void
    // {
    //     static::$mustCheckBranchId = true;
    // }
    
    // protected function getBranchIdRequirmentStatus() : string
    // {
    //     return static::$mustCheckBranchId ? "required" : "nullable";
    // }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function getUserModelPropChangers() : array
    {
        $userModelClass = $this->getUserModelClass();
        return AdminAssignablePropsManager::Singleton()->getSensitivePropChangersForClass($userModelClass);
    }
    
    protected function getModelAssignablePropsRules() : array
    {
        $propChangers = $this->getUserModelPropChangers();
        $propsRules = [];

        foreach($propChangers as $propChanger)
        {
            if($propChanger instanceof HasValidationRules)
            {
                $propsRules[$propChanger->getPropName()] = $propChanger->getValidationRules();
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
        return array_merge($rules , $this->getModelAssignablePropsRules());
        
            "role_id" => [  $this->getRoleIdRequirmentStatus() , "integer", "exists:roles,id"],
            "department_id" => [ $this->getDepartmentIdRequirmentStatus() , "integer", "exists:departments,id"],
            "branch_id" => [$this->getBranchIdRequirmentStatus()  , "integer", "exists:departments,id"]
        
    }
}
