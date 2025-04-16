<?php
 
namespace  PixelApp\Services\UsersManagement\EmailChangerService;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserChangeEmailRequest;
use PixelApp\Interfaces\EmailAuthenticatable; 
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\EmailChangerService\EmailChangerService as BaseEmailChangerService;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UsersManagement\Traits\EditableUserCheckingMethods;

/**
 * @property PixelUser $model
 */
class EmailChangerService extends BaseEmailChangerService
{
    use EditableUserCheckingMethods;
 
    protected function checkPreConditions() : void
    {
        $this->checkDefaultAdmin($this->model);
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( UserChangeEmailRequest::class);
    }
 
    protected function appendAuthenticatabePrimaryToRequest() : void
    {
        request()->merge([
                            $this->model->getKeyName() => $this->model->getKey()
                        ]);
    }
    public function __construct(EmailAuthenticatable $model)
    { 
        parent::__construct($model);
        $this->appendAuthenticatabePrimaryToRequest();
        $this->catchModelEmailOldValue();
    }

    protected function catchModelEmailOldValue() : void
    {
        $emailKey =  $this->model->getEmailColumnName();
        $this->model->catchKeyValueTemporarlly($emailKey);
    }

    //on this point ... email change has been checked
    protected function fireCommittingEvents() : void
    {
       PixelTenancyManager::handleTenancySyncingData($this->model);
          
       parent::fireCommittingEvents();
    }
}
