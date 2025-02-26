<?php

namespace PixelApp\Notifications\Company;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PixelApp\Models\CompanyModule\TenantCompany;

class TenantCompanyForgettingIdNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected  TenantCompany $company;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(  TenantCompany $company)
    {
        $this->company = $company;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("Dear {$this->company->name} company's default admin")
                    ->line("You are receiving this email based on your request to get your company id")
                    ->line("Your company id is : {$this->company->company_id} ")
                    ->line("Please use it to login into your company account by the link bellow !")
                    ->action('login page' , url('/'))
                    ->line('Thank you for using our application!');
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
