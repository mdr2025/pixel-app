<?php

namespace PixelApp\Notifications\UserNotifications\StatusNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InactiveRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(  )
    {

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
                        ->subject(" Your account Has Been Deactivated")
                        ->line("Dear ... " . $notifiable->name )
                        ->line("Your Account Has Been Deactivated By System Administrator")
                        ->line("If You Think That Something is Wrong ... Contact The System Administrator");
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
