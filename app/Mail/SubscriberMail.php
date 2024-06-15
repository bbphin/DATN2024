<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->view('emails.subscriber')->subject("Đăng ký nhận thông báo từ " . env('APP_NAME') . " thành công");
    }
}
