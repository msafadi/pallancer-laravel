<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderCreatedEmail;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCreatedEmail
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
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $name = $order->user->name;
        $order_id = $order->id;
        $action = url(route('orders'));
        $email = User::where('type', 'super-admin')->first()->email;
        Mail::send(new OrderCreatedEmail($email, $name, $order_id, $action));
    }
}
