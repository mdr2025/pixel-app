<?php

namespace PixelApp\Interfaces;

interface EmailAuthenticatable
{
    public function getEmailColumnName() : string;
    public function getEmailVerificationDateColumnName() : string;
    public function getEmailVerificationTokenColumnName() : string;
    public function isVerified() : bool;
    public function notify($instance) ;
}
