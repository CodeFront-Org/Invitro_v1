<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reorder extends Mailable
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
    public $table;
    public function __construct($link, $recipient,$name,$table)
    {
        $this->link = $link;
        $this->recipient = $recipient;
        $this->link = $name;
        $this->table = $table;
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
        $table=$this->table;
        return $this->markdown('emails.reorder',compact('link','name','table'));
    }
}
