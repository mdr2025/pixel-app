<?php

namespace PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationBaseServices;
 
use PixelApp\Interfaces\EmailAuthenticatable; 
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\VerificationNotificationSenderRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationNotificationSenders\EmailAuthenticatableVerificationNotificationSender;

abstract class VerificationNotificationResendingService
{
    use GeneralValidationMethods;

    protected string $AuthenticatableEmail = "";
    protected ?EmailAuthenticatable $EmailAuthenticatable = null;

    abstract protected function getAuthenticatableClass()  :string;
    
    abstract protected static function getVerificationFrontendURI() : string;

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(VerificationNotificationSenderRequest::class);
    }

    /**
     * @param string $AuthenticatableEmail
     * @return $this
     */
    public function setAuthenticatableEmail(string $AuthenticatableEmail): self
    {
        $this->AuthenticatableEmail = $AuthenticatableEmail;
        return $this;
    }

    /**
     * @param EmailAuthenticatable|null $EmailAuthenticatable
     * @return $this
     */
    public function setAuthenticatable(?EmailAuthenticatable $EmailAuthenticatable): self
    {
        $this->EmailAuthenticatable = $EmailAuthenticatable;
        return $this;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getAuthenticatableEmail(): string
    {
        if(!$this->AuthenticatableEmail)
        {
            /** validating the request ... because the email isn't set by setter method */
            $this->initValidator()->validateRequest()->setRequestData();

            return $this->data["email"];
        }
        return $this->AuthenticatableEmail;
    }

 
    /**
     * @return string|null
     *
     * -This method to allow the child classes to override the path if its needed
     */
    protected static function getFrontendRootPath() : ?string
    {
        /**
         * If it return null ===>  EmailAuthenticatableVerificationNotificationSender's VerificationLinkGenerator
         * will use the default root path found in env
         */
        return env('MAIL_LINK');
    }

    /**
     * @throws Exception
     *
     * This method is static to allow the event listeners to correctly initialize an EmailAuthenticatableVerificationNotificationSender by the service
     */
    public static function initVerificationNotificationSender(EmailAuthenticatable $EmailAuthenticatable) : EmailAuthenticatableVerificationNotificationSender
    {
        return (new EmailAuthenticatableVerificationNotificationSender($EmailAuthenticatable))
                                                                     ->setVerificationFrontendURI( static::getVerificationFrontendURI())
                                                                     ->setFrontendRootPath( static::getFrontendRootPath() );
    }

    protected function sendEmailVerificationNotification() : bool
    {
        return $this::initVerificationNotificationSender( $this->EmailAuthenticatable )
                    ->resetVerificationToken()
                    ->sendEmailVerificationNotification() ;
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

        $authenticatable = new $authenticatableClass;
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
     * @return Model|EmailAuthenticatable
     * @throws Exception
     */
    protected function fetchAuthenticatable() : Model|EmailAuthenticatable
    {
        $authenticatable = $this->initEmailAuthenticatable();
        $authenticatableEmailColumn = $authenticatable->getEmailColumnName();
        return $authenticatable::query()->where($authenticatableEmailColumn , $this->getAuthenticatableEmail())->first()
                ??
                throw new Exception("Failed To Send Verification Link ... User Not Found !");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function setProcessableAuthenticatable( ): void
    {
        if(!$this->EmailAuthenticatable)
        {
            $this->EmailAuthenticatable = $this->fetchAuthenticatable();
        }
    }

    /**
     * @return JsonResponse
     */
    public function resend(): JsonResponse
    {
        try {
            /** Doesn't need to validate request everytime ... because the email can be set by setter method */

            $this->setProcessableAuthenticatable();
            if( $this->sendEmailVerificationNotification() )
            {
                return Response::success([], ["Verification Link Has Sent Successfully"]);
            }
            throw new Exception("Failed to send email verification message !");

        } catch (Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }
}
