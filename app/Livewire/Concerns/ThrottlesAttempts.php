<?php

namespace App\Livewire\Concerns;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Limits how often a Livewire action can run for a given key, failing with the standard throttle message.
 */
trait ThrottlesAttempts
{
    /**
     * Count an attempt against the key, or fail on the given field once the limit is reached.
     *
     * @throws ValidationException
     */
    protected function throttleAttempt(string $key, int $maxAttempts, int $decaySeconds, string $errorField): void
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            event(new Lockout(request()));

            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                $errorField => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }
}
