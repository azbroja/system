<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceMade extends Notification implements ShouldQueue
{
    use Queueable;
    public $newInvoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($newInvoice)
    {
        $this->newInvoice = $newInvoice;
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
            ->subject('Wystawiono Fakturę')
            ->line('Faktura ' . $this->newInvoice->number . ' dla ' . $this->newInvoice->buyer_address__name . ' została właśnie wystawiona')
            ->action('Link', url('/invoice/' . $this->newInvoice->id))
            ->line('Życzę owocnej pracy!');
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
