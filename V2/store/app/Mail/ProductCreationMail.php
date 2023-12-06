<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $recipient;
    public $name;
    public $product_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $recipient,$name,$product_name)
    {
        $this->link = $link;
        $this->recipient = $recipient;
        $this->name = $name; // Fix: Assign value to $this->name
        $this->product_name = $product_name;
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
        return $this->markdown('emails.create_product',compact('link','name','product_name'));
    }
}
