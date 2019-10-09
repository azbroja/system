<?php

namespace App\Notifications;

use App\Customer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class SendOfferPromotion extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
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
        $offer = $this->offer;
        $offer_products = $offer->products;
        $user = User::findOrFail($offer->user_id);
        $customer = Customer::findOrFail($offer->customer_id);
        $contactPerson = $customer->contact_people()->first();
        $seller = Customer::where('id', '=', '1')->first();

        return (new MailMessage)
            ->from($user->email, ' ' . $user->name . ' ' . $user->surname)
            ->subject('Oferta Promocyjna na Tonery do Drukarek firmy  ')
            ->view('vendor.notifications.offer_promotion', [
                'offer' => $offer,
                'user' => $user,
                'customer' => $customer,
                'contactPerson' => $contactPerson,
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
