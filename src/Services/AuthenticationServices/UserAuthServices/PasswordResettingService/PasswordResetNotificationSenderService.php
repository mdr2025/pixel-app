<?php


namespace PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService;

use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PixelApp\Exceptions\JsonException;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\ForgetPasswordRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PasswordReset;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\SignatureEmailLinkGenerators\SignatureEmailLinkGenerator;
use PixelApp\Notifications\UserNotifications\ResetPasswordFormLinkNotification;
use PixelApp\Services\AuthenticationServices\UserAuthServices\LogoutService\LogoutService;
use PixelApp\Services\PixelServiceManager;

class PasswordResetNotificationSenderService
{
    use GeneralValidationMethods;

    protected PixelUser $user;
    protected static SignatureEmailLinkGenerator $passwordResettingLinkGenerator;

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ForgetPasswordRequest::class);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generatePasswordResetLink(): string
    {
        return  $this::$passwordResettingLinkGenerator->generateLink();
    }

    protected function initNotification() : Notification
    {
        return new ResetPasswordFormLinkNotification( $this->generatePasswordResetLink() );
    }
    protected function notifyUser() : self
    {
        $this->user->notify( $this->initNotification() );
        return $this;
    }

    protected function getPasswordResetModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(PasswordReset::class);
    }

    /**
     * @return $this
     * @throws JsonException
     */
    private function createPasswordResetModel(): self
    {
        $this->data["token"] = $this::$passwordResettingLinkGenerator->getGeneratedToken();

        $modelClass = $this->getPasswordResetModelClass();

        if (!$modelClass::create($this->data))
        {
            throw new JsonException("Failed To Create Reset Password Token For The Given User");
        }
        return $this;
    }
    public static function getPasswordResettingFrontendURI() : string
    {
        return "user-reset-password";
    }
    public static function initVerificationLinkGenerator(EmailAuthenticatable $EmailAuthenticatable  ) : void
    {
        static::$passwordResettingLinkGenerator = (new SignatureEmailLinkGenerator($EmailAuthenticatable))
                                                        ->setFrontendURI( static::getPasswordResettingFrontendURI() );
    }
    /**
     * @return $this
     * @throws JsonException
     * @throws Exception
     */
    protected function sendResetLinkNotification(): self
    {
        $this->initVerificationLinkGenerator($this->user);
        return $this->createPasswordResetModel()->notifyUser();
    }

    protected function deleteOldPasswordResetModels() : self
    {
        $modelClass = $this->getPasswordResetModelClass();
        
        $modelClass::where("email" , $this->user->email)->delete();
        return $this;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
    
    protected function fetchUser(): PixelUser|null
    {
        return $this->getUserModelClass()::where("email", $this->data["email"])->first();
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function setUser() : self
    {
        if (!$user = $this->fetchUser())
        {
            throw new JsonException("User Not Found In Our Databases ... Check Your Info Then Try Again");
        }
        $this->user = $user;
        return $this;
    }

    public function send(): JsonResponse
    {
        try {
            /** Validation operations */
            $this->initValidator()->validateRequest()->setRequestData();

            DB::beginTransaction();
            /** Password resetting operations */
            $this->setUser()->deleteOldPasswordResetModels()->sendResetLinkNotification();
            DB::commit();
            return Response::success([], ["Password Reset Link Has Been Sent Successfully"], 200);
        } catch (Exception $e)
        {
            DB::rollBack();
            return Response::error([$e->getMessage()]);
        }
    }
}
