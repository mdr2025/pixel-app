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
    protected bool $requiredToResetingVerficationToken = false;
    
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
        if(!static::$verificationLinkGenerator)
        {
            static::$verificationLinkGenerator = new SignatureEmailLinkGenerator($EmailAuthenticatable);
        }
        return static::$verificationLinkGenerator;
    }

    /**
     * @return SignatureEmailLinkGenerator
     * 
     * it is always called after constructor is called ... so the link generator object != null
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

        if(
            $this->requiredToResetingVerficationToken 
            ||
            !$AuthenticatableVerificationPropsChanger->doesItHaveVerificationTokenValue()
          )
        {
            $AuthenticatableVerificationPropsChanger->requireToVerify( $this::$verificationLinkGenerator )
                                                    ->changeAuthenticatablePropOrFail();

            return $this->EmailAuthenticatable->save();
        }

        //no need to handle a new verification prop value
        return true;
        
    }

    public function resetVerificationToken() : self
    {
        $this->requiredToResetingVerficationToken = true;
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function sendEmailVerificationNotification(): bool
    {
        if($this->setAuthenticatableVerificationProps()) 
        {
            $this->notifyEmailAuthenticatable();
            return true;    
        }

        return false;
    }
}
