<?php

namespace PixelApp\Notifications\UserNotifications\StatusNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActiveRegistrationNotification extends Notification implements ShouldQueue
{
     use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
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
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(" Your account Registration Status")
            ->line("Dear ... " . $notifiable->name)
            ->line("Your Account Registration Has Completed Successfully !")
            ->line("Thanks for your registration in our system ")
            ->line("Kindly you can use this link so as to log in with your Registered Email And Password ")
            ->line(" Link :" . url(env("FRONTEND_APP_URL") . "/users-login"))
            ->line("Note : To Get Company Id Contact Your Administrator/ IT Team");
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
