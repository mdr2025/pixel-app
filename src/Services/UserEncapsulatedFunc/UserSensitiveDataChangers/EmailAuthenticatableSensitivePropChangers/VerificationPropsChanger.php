<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers;

use Exception;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\SignatureEmailLinkGenerators\SignatureEmailLinkGenerator;
use PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\VerificationFuncs\VerificationNotificationSenders\EmailAuthenticatableVerificationNotificationSender;

class VerificationPropsChanger extends EmailAuthenticatableSensitivePropChanger
{
    protected bool $verified = false;
    protected ?SignatureEmailLinkGenerator $verificationLinkGenerator = null;

    public function getPropName() : string
    {
        return $this->authenticatable->getEmailVerificationDateColumnName();
    }

    public function getEmailVerificationTokenColumnName() : string
    {
        return $this->authenticatable->getEmailVerificationTokenColumnName();
    }

    public function doesItHaveVerificationTokenValue() : bool
    {
        $verificationTokenPropName = $this->getEmailVerificationTokenColumnName();
        return $this->authenticatable->{ $verificationTokenPropName } != null;
    }

    /**
     * @throws Exception
     */
    protected function getValidVerificationLinkGenerator(?SignatureEmailLinkGenerator $verificationLinkGenerator = null):SignatureEmailLinkGenerator
    {
        return $verificationLinkGenerator ??
        EmailAuthenticatableVerificationNotificationSender::initVerificationLinkGenerator( $this->authenticatable );
            //    VerificationNotificationResendingService::initVerificationNotificationSender( $this->authenticatable )::getVerificationLinkGenerator();
    }

    /**
     * @param SignatureEmailLinkGenerator|null $verificationLinkGenerator
     * @return $this
     * @throws Exception
     */
    public function setVerificationLinkGenerator(?SignatureEmailLinkGenerator $verificationLinkGenerator): self
    {
        $this->verificationLinkGenerator = $this->getValidVerificationLinkGenerator($verificationLinkGenerator);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function requireToVerify(?SignatureEmailLinkGenerator $verificationLinkGenerator = null) : self
    {
        $this->verified  = false;
        $this->setVerificationLinkGenerator($verificationLinkGenerator);
        return $this;
    }

    public function verify() : self
    {
        $this->verified  = true;
        return $this;
    }

    protected function getVerifiedAuthenticatableChangesArray() : array
    {
        return [
            $this->getPropName() =>  now(),
            $this->getEmailVerificationTokenColumnName()  => null
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function checkVerificationLinkGeneratorOrFail() : void
    {
        if(!$this->verificationLinkGenerator)
        {
            throw new Exception("Can't generate a new verification token ... verificationLinkGenerator is not set !");
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getUnVerifiedAuthenticatableChangesArray() : array
    {
        $this->checkVerificationLinkGeneratorOrFail();

        return [
                    $this->getPropName()  => null,
                    $this->getEmailVerificationTokenColumnName() => $this->verificationLinkGenerator->getGeneratedToken() ?: null
               ];
    }

    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        if($this->verified)
        {
            return $this->getVerifiedAuthenticatableChangesArray();
        }

        return $this->getUnVerifiedAuthenticatableChangesArray();
    }

}
