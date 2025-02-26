<?php

namespace PixelApp\Listeners;

use PixelApp\Events\ResendEmailTokenEvent; 

/**
 * For remove later
 */
class ResendEmailToken
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ResendEmailTokenEvent  $event
     * @return void
     */
    public function handle(ResendEmailTokenEvent $event)
    {
        $model = $event->model;
        $email = match (class_basename($model)) {
            "User" => "email",
            "Company" => "admin_email",
        };
        $token = generateVerificationToken($model, $email);
        $tokenLink = getVerificationLink($token, mb_strtolower(class_basename($model)));
        sendEmailVerification($model, null, null, $tokenLink,  $email);
    }
}
