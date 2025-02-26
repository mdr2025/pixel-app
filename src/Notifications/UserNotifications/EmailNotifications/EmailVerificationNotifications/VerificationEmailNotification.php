<?php

namespace PixelApp\Notifications\UserNotifications\EmailNotifications\EmailVerificationNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $verificationLink ;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( string $verificationLink )
    {
        $this->verificationLink = $verificationLink;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return  MailMessage
     */
    public function toMail($notifiable) : MailMessage
    {
        return (new MailMessage())
                        ->subject("Verification Token For Your Email Address")
                        ->line("Dear ... " . $notifiable->name )
                        ->line('Please Click On The Link Bellow To Verify Your Email In Our System')
                        ->action( "Verification Page" , $this->verificationLink) ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
