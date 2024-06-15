<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailContent,$subject;
    /**
     * Create a new message instance.
     */
    public function __construct($subject,$mailContent)
    {
        $this->subject = $subject;
        $this->mailContent = $mailContent;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.sendsubscriber')
            ->with([
                'content' => $this->mailContent,
            ])->subject($this->subject);
    }
}
