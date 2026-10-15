<?php

namespace App\Listeners;

use App\Models\Login;
use Illuminate\Auth\Events\Login as LoginEvent;

class RecordLogin
{
    public function handle(LoginEvent $event): void
    {
        $userId = $event->user->getAuthIdentifier();

        Login::query()->create(['user_id' => $userId]);

        $event->user::query()->whereKey($userId)->update(['last_active_at' => now()]);
    }
}
