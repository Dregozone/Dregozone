<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use App\Livewire\Concerns\ThrottlesAttempts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class VerifyEmail extends Component
{
    use ThrottlesAttempts;

    /**
     * Verification emails a user can request within the decay window.
     */
    protected const MAX_ATTEMPTS = 3;

    /**
     * Length of the rate limiting window, in seconds.
     */
    protected const DECAY_SECONDS = 600;

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        $this->throttleAttempt('verification|'.Auth::id(), self::MAX_ATTEMPTS, self::DECAY_SECONDS, 'verification');

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}
