<?php

namespace PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\VerificationTokenRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\VerificationPropsChanger;
use PixelApp\Notifications\UserNotifications\EmailNotifications\EmailVerificationNotifications\EmailVerificationCompletedNotification;

abstract class EmailVerificationService
{
    use GeneralValidationMethods;

    protected Model | EmailAuthenticatable $EmailAuthenticatable;

    abstract protected function getAuthenticatableClass() : string;
    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(VerificationTokenRequest::class);
    }
    protected function notifyAuthenticatable(): void
    {
        $this->EmailAuthenticatable->notify(new EmailVerificationCompletedNotification());
    }

    /**
     * @throws Exception
     */
    protected function verifyAuthenticatable(): self
    {
        /**  @var VerificationPropsChanger $AuthenticatableVerificationPropsChanger  */
        $AuthenticatableVerificationPropsChanger = new VerificationPropsChanger($this->EmailAuthenticatable);
        $AuthenticatableVerificationPropsChanger->verify()->changeAuthenticatablePropOrFail();

        return $this->EmailAuthenticatable->save() ?
               $this :
               throw new Exception("Failed to verify EmailAuthenticatable");
    }


    /**
     * @return Model|EmailAuthenticatable
     * @throws Exception
     */
    protected function initEmailAuthenticatable() : Model | EmailAuthenticatable
    {
        $authenticatableClass = $this->getAuthenticatableClass();
        if(!class_exists($authenticatableClass))
        {
            throw new Exception("Invalid EmailAuthenticatable Class provided !");
        }

        $authenticatable = new $authenticatableClass();
        if(
            !$authenticatable instanceof Model
            ||
            ! $authenticatable instanceof EmailAuthenticatable
        )
        {
            throw new Exception("Invalid EmailAuthenticatable Class provided !");
        }

        return $authenticatable;
    }

    /**
     * @return Model|EmailAuthenticatable|null
     * @throws Exception
     */
    protected function fetchAuthenticatable() : Model|EmailAuthenticatable|null
    {
        $authenticatable = $this->initEmailAuthenticatable();
        $authenticatableVerificationTokenColumn = $authenticatable->getEmailVerificationTokenColumnName();
        return $authenticatable::query()->where($authenticatableVerificationTokenColumn , $this->data["token"])->first();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function setProcessableAuthenticatable( ): void
    {
        if($Authenticatable = $this->fetchAuthenticatable())
        {
            $this->EmailAuthenticatable = $Authenticatable;
            return;
        }
        throw new Exception("Failed to verify this authenticatable ... Not Found !");
    }

    /**
     * @return JsonResponse
     */
    public function verify(): JsonResponse
    {
        try {
            $this->initValidator()->validateRequest()->setRequestData();
            $this->setProcessableAuthenticatable();

            $this->verifyAuthenticatable()->notifyAuthenticatable();
            return Response::success([], ["Verification Completed Successfully"]);

        } catch (Exception $e) {
            return Response::error([$e->getMessage()]);
        }
    }
}
