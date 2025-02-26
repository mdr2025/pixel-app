<?php

namespace PixelApp\Traits\interfacesCommonMethods;


trait EmailAuthenticatableMethods
{
    public function getEmailColumnName(): string
    {
        return "email";
    }
    public function getEmailVerificationDateColumnName(): string
    {
        return "email_verified_at";
    }
    public function getEmailVerificationTokenColumnName(): string
    {
        return "verification_token";
    }
    public function isVerified(): bool
    {
        return (bool) $this->{ $this->getEmailVerificationDateColumnName() };
    }
}
