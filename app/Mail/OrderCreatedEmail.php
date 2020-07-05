<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    
    protected $name;

    protected $order_id;

    protected $action;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name, $order_id, $action)
    {
        $this->email = $email;
        $this->name = $name;
        $this->order_id = $order_id;
        $this->action = $action;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('New Order Created');
        $this->from('no-reply@example.com', config('app.name'));
        $this->to($this->email);
        return $this->view('mails.order-created', [
            'name' => $this->name,
            'order_id' => $this->order_id,
            'action' => $this->action,
        ]);
    }
}
