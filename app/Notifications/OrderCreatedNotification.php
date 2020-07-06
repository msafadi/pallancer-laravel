<?php

namespace App\Notifications;

use App\Channels\HotSms;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
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
        return ['mail', 'database', 'broadcast', /*'nexmo',*/ HotSms::class]; // toMail, toDatabase, toBroadcast, toNexmo

        $via = ['database']; // toDatabase

        if ($notifiable->email_notify) {
            $via[] = 'mail';
        }
        if ($notifiable->sms_notify) {
            $via[] = 'nexmo'; // toNexmo
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;
        $message->subject('New Order')
                ->greeting('Hello ' . $notifiable->name)
                ->line('A new order has been created (Order #' . $this->order->id . ').')
                ->action('View Order', url(route('orders')))
                ->line('Thank you for using our application!');
                //->view('mails.order-crearted');
            
        return $message;
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new order has been created (Order #' . $this->order->id . ')',
            'action' => route('orders'),
            'icon' => '<i class="fas fa-file-invoice"></i>',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'message' => 'A new order has been created (Order #' . $this->order->id . ')',
            'action' => route('orders'),
            'icon' => '<i class="fas fa-file-invoice"></i>',
            'order' => $this->order,
        ];
    }

    public function toNexmo($notifiable)
    {
        $message = new NexmoMessage();
        $message->content('A new order created!')
            ->unicode()
            ->from('Test');

        return $message;
    }

    public function toHotsms($notifiable)
    {
        return 'A new order created.';
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
