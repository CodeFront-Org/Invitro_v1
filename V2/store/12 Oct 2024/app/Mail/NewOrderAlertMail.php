<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $recipient;
    public $name;
    public $product_name;
    public $batch_no;
    public $quantity;
    public $destination;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $recipient, $name, $product_name, $batch_no, $quantity, $destination)
    {
        $this->link = $link;
        $this->recipient = $recipient;
        $this->name = $name; // Fix: Assign value to $this->name
        $this->product_name = $product_name;
        $this->batch_no = $batch_no;
        $this->quantity = $quantity;
        $this->destination = $destination;
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
        $batch_no = $this->batch_no;
        $quantity = $this->quantity;
        $destination = $this->destination;
        
        return $this->view('emails.new_order', compact('link', 'name', 'product_name', 'quantity', 'batch_no', 'destination'));
    }
}
