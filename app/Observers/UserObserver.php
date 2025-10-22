<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\PasswordChanged;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Notify the user if their password was changed
        if ($user->wasChanged('password')) {
            $ipAddress = request()->ip() ?? 'unknown';
            $user->notify(new PasswordChanged($ipAddress));
        }
    }
}

