<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\ThrottlesAttempts;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    use ThrottlesAttempts;

    /**
     * Reset link requests allowed per IP address within the decay window.
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Length of the rate limiting window, in seconds.
     */
    protected const DECAY_SECONDS = 600;

    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->throttleAttempt('password-reset|'.request()->ip(), self::MAX_ATTEMPTS, self::DECAY_SECONDS, 'email');

        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}
