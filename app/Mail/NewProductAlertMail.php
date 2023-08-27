<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewProductAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $link;
    public $recipient;
    public $name;
    public $product_name;
    public $expiry;
    public $batch_no;
    public $staff;

    public function __construct($link, $recipient,$name,$product_name,$expiry,$batch_no,$staff)
    {
        $this->link = $link;
        $this->recipient = $recipient;
        $this->link = $name;
        $this->product_name = $product_name;
        $this->expiry = $expiry;
        $this->batch_no = $batch_no;
        $this->staff = $staff;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        $link=$this->link;
        $name=$this->name;
        $product_name=$this->product_name;
        $expiry=$this->expiry;
        $batch_no=$this->batch_no;
        $staff=$this->staff;
        return $this->markdown('emails.new_product',compact('link','name','product_name','expiry','batch_no','staff'));
    }
}
