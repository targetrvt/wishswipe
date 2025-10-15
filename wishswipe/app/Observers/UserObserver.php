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
        // Temporary debug logging to verify observer execution and change detection
        Log::info('UserObserver.updated triggered', [
            'user_id' => $user->id,
            'wasChangedPassword' => $user->wasChanged('password'),
            'isDirtyPassword' => $user->isDirty('password'),
        ]);

        // Notify the user if their password was changed
        if ($user->wasChanged('password')) {
            $ipAddress = request()->ip() ?? 'unknown';
            $user->notify(new PasswordChanged($ipAddress));
            Log::info('PasswordChanged notification queued', ['user_id' => $user->id]);
        }
    }
}

