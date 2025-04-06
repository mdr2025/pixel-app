<?php
 
namespace  PixelApp\Services\UsersManagement\EmailChangerService;

use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserChangeEmailRequest;
use PixelApp\Interfaces\EmailAuthenticatable; 
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\EmailChangerService\EmailChangerService as BaseEmailChangerService;

class EmailChangerService extends BaseEmailChangerService
{
    
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
    }
 
}
