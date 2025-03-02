<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\UserRegisteringServices;
 
use PixelApp\Events\EmailAuthenticatableEvents\EmailAuthenticatableRegisteredEvent;
use PixelApp\Interfaces\EmailAuthenticatable;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\SingleRowStoringService;
use Exception;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\RegisterRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\PasswordChanger;
use PixelApp\Models\UsersModule\PixelUser;

/**
 * @property EmailAuthenticatable | PixelUser $Model
 */
class UserRegisteringService extends SingleRowStoringService
{

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(RegisterRequest::class); 
    }

    protected function getModelClass(): string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "User Has Not Created !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "Your account has been created successfully ... Please verify your email address from the link you have got into your email !";
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function PasswordValueHandler(array $data): void
    {
        (new PasswordChanger())->setData($data)->setAuthenticatable($this->Model)->changeAuthenticatableProp();
    }

    /**
     * @throws Exception
     */
    protected function doBeforeSavingCurrentModelProps(array $currentDataRow = []): void
    {
        $this->Model->generateName();
        $this->PasswordValueHandler($currentDataRow);
    }

    protected function doBeforeSuccessResponding(): void
    {
        event(new EmailAuthenticatableRegisteredEvent($this->Model));
    }
}
