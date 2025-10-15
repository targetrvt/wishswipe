<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $ipAddress;

    public function __construct(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your WishSwipe password was changed')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('We wanted to let you know that your account password was just changed.')
            ->line('Time: ' . now()->toDateTimeString())
            ->line('IP Address: ' . $this->ipAddress)
            ->line('If you made this change, no action is needed.')
            ->line('If you did not make this change, please reset your password immediately and contact support.');
    }
}


