<?php

namespace PixelApp\Notifications\UserNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordFormLinkNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $resetFormLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $resetFormLink)
    {
        $this->resetFormLink = $resetFormLink;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Your IGS Account Password's Reset Link")
                    ->line("Dear " . $notifiable->name)
                    ->line('By this link you will be able to reset your password')
                    ->action("Form 's Link", $this->resetFormLink)
                    ->line("You have received this email based on your password reset request ")
                    ->line("If you didn't request for password reset ... Contact the system administrator");
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
