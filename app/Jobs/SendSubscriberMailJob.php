<?php

namespace App\Jobs;

use App\Mail\SendSubscriberMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscriberMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subject,$mailContent,$email;
    public function __construct($subject, $mailContent, $email)
    {
        $this->subject = $subject;
        $this->mailContent = $mailContent;
        $this->email = $email;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new SendSubscriberMail($this->subject, $this->mailContent));
    }

}
