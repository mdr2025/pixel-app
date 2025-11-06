<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices;

use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use PixelApp\Services\CoreServices\ManyToManySelectedRequestDataMerger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitiveDataChanger;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers\StatusChangerTypes\SignUpAccountStatusChangerServices\SignUpAccountApprovingService as BaseSignUpAccountApprovingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers\AdminAssignablePropsManager;
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;

class SignUpAccountApprovingService extends BaseSignUpAccountApprovingService
{
    use EditableUserCheckingMethods;
  
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( static::getApprovingRequestFormBaseClass() );
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    protected function getSignUpApprovmentPropChangers() : array
    {
        $userModelClass = $this->getUserModelClass();
        return AdminAssignablePropsManager::Singleton()->getSensitivePropChangersForClass($userModelClass);
    }  
    
    protected function initUserSensitiveDataChanger() : UserSensitiveDataChanger
    {
        return new UserSensitiveDataChanger($this->model , $this->data);
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function approve(): JsonResponse
    {
        $branchModel = $this->getBranchModelClass();
        /**
         * @todo
         */
        ManyToManySelectedRequestDataMerger::mergeData('accessibleBranches',  $branchModel , 'accessible_branches');

        return parent::approve();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function setUserRelationships(): self
    { 
        /** Will change user's props by reference */
        $this->initUserSensitiveDataChanger()
             ->changeProps($this->getSignUpApprovmentPropChangers());
        /**
         * @todo
         */
        // Handle accessibleBranches relationship
        if (isset($this->data['accessibleBranches'])) {
            $this->model->accessibleBranches()->sync($this->data['accessibleBranches']);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function changeAuthenticatableStatus(): self
    {
        parent::changeAuthenticatableStatus();
        return $this->setUserRelationships();
    }

    protected function getNotificationClass() : ?string
    {
        return $this->model->status == "active" 
               ? ActiveRegistrationNotification::class
               : null;
    }
}
