<?php

namespace App\Notifications;

use Filament\Facades\Filament;
use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;

class ResetPassword extends BaseNotification
{
    /**
     * Get the reset password URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }
}

