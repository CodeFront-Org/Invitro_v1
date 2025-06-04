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
    public $product_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $recipient,$product_name)
    {
        $this->link = $link;
        $this->recipient = $recipient;
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
        $product_name = $this->product_name;
        return $this->markdown('emails.landing_cost',compact('link','product_name'));
    }
}
