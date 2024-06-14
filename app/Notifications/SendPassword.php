<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPassword extends Notification
{
    use Queueable;

    protected $password,$user;

    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Xin chào: '.$this->user->name )
            ->line('Mật khẩu của bạn là: ' . $this->password)
            ->line('Cảm ơn bạn đã sử dụng dịch vụ.');
    }
}
