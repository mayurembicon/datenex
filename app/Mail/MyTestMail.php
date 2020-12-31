<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attachment = $this->details['attachment'];
        $title = $this->details['title'];
        $type=$this->details['type'];
        $mail = $this->subject($title);
        if (!empty($attachment)) {
            $mail->attachData($attachment, $type.'.pdf');
        }
        $mail1 = $mail->view('emails.myTestMail');
        return $mail1;

    }
}
