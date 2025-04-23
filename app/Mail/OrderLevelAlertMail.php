<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderLevelAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $recipient;
    public $name;
    public $product_name;
    public $current_order_qty;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $recipient,$name,$product_name,$current_order_qty)
    {       
        $this->link = $link;
        $this->recipient = $recipient;
        $this->name = $name; // Fix: Assign value to $this->name
        $this->product_name = $product_name;
        $this->current_order_qty = $current_order_qty;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = $this->link;
        $name = $this->name; // Fix: Assign value to $name
        $product_name = $this->product_name;
        $current_order_qty = $this->current_order_qty;
        return $this->view('emails.order_level',compact('name','product_name','current_order_qty'));
    }
}
