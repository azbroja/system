<?php

namespace App\Notifications;

use App\Customer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class SendWaybill extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
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
        $order = $this->order;
        $user = User::findOrFail($order->user_id);
        $customer = Customer::findOrFail($order->customer_id);
        $seller = Customer::where('id', '=', '1')->first();

        return (new MailMessage)
            ->from($user->email, ' ' . $user->name . ' ' . $user->surname)
            ->subject('Potwierdzenie nadania zamówienia  ')
            ->view('vendor.notifications.waybill', [
                'order' => $order,
                'user' => $user,
                'customer' => $customer,
                'seller' => $seller,
            ]);

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
