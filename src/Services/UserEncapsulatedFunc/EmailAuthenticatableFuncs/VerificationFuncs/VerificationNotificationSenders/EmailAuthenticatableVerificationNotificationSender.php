<?php

namespace PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationNotificationSenders;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Notifications\UserNotifications\EmailNotifications\EmailVerificationNotifications\VerificationEmailNotification;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\SignatureEmailLinkGenerators\SignatureEmailLinkGenerator;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\VerificationPropsChanger;
use Stancl\Tenancy\Database\Models\Domain;

class EmailAuthenticatableVerificationNotificationSender
{

    protected Model | EmailAuthenticatable $EmailAuthenticatable;
    protected static ?SignatureEmailLinkGenerator $verificationLinkGenerator = null;

    /**
     * @throws Exception
     */
    public function __construct(mixed $EmailAuthenticatable)
    {
        $this->setEmailAuthenticatable($EmailAuthenticatable);
        $this::initVerificationLinkGenerator($EmailAuthenticatable);
    }

    public static function initVerificationLinkGenerator(EmailAuthenticatable $EmailAuthenticatable) : SignatureEmailLinkGenerator
    {
        if(static::$verificationLinkGenerator)
        {
            static::$verificationLinkGenerator = new SignatureEmailLinkGenerator($EmailAuthenticatable);
        }
        return static::$verificationLinkGenerator;
    }

    /**
     * @return SignatureEmailLinkGenerator
     */
    public function getVerificationLinkGenerator(): SignatureEmailLinkGenerator
    {
        return self::$verificationLinkGenerator;
    }
    
    /**
     * @param string|Domain $domain
     * @return $this
     */
    public function setCompanyDomain(string | Domain $domain): self
    {
        $this::$verificationLinkGenerator->setCompanyDomain($domain);
        return $this;
    }
    /**
     * @param string|null $verificationFrontendURI
     * @return $this
     */
    public function setVerificationFrontendURI(?string $verificationFrontendURI): self
    {
        $this::$verificationLinkGenerator->setFrontendURI($verificationFrontendURI);
        return $this;
    }

    /**
     * @param string|null $frontendRootPath
     * @return $this
     */
    public function setFrontendRootPath(?string $frontendRootPath):self
    {
        $this::$verificationLinkGenerator->setFrontendRootPath($frontendRootPath);
        return $this;
    }


    /**
     * @param mixed $EmailAuthenticatable
     * @return $this
     * @throws Exception
     */
    public function setEmailAuthenticatable( mixed $EmailAuthenticatable): self
    {
        if(!$EmailAuthenticatable instanceof Model || !$EmailAuthenticatable instanceof EmailAuthenticatable)
        {
            throw new Exception("Email Authenticatable user must be a Model child type and must implement EmailAuthenticatable interface");
        }
        $this->EmailAuthenticatable = $EmailAuthenticatable;
        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function notifyEmailAuthenticatable(): void
    {
       $verificationLink = $this::$verificationLinkGenerator->generateLink();
       $this->EmailAuthenticatable->notify( ( new VerificationEmailNotification($verificationLink)) ) ;
    }

    /**
     * @throws Exception
     */
    protected function setAuthenticatableVerificationProps(): string|bool
    {
        /**  @var VerificationPropsChanger $AuthenticatableVerificationPropsChanger  */
        $AuthenticatableVerificationPropsChanger = new VerificationPropsChanger($this->EmailAuthenticatable);

        $AuthenticatableVerificationPropsChanger->requireToVerify( $this::$verificationLinkGenerator )
                                                ->changeAuthenticatablePropOrFail();

        return $this->EmailAuthenticatable->save();
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function sendEmailVerificationNotification(): bool
    {
        if(!$this->setAuthenticatableVerificationProps()) { return false;}

        $this->notifyEmailAuthenticatable();
        return true;
    }
}
