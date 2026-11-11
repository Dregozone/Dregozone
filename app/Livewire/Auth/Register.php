<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\ThrottlesAttempts;
use App\Livewire\Concerns\VerifiesTurnstile;
use App\Mail\AdminNotifier;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    use ThrottlesAttempts, VerifiesTurnstile;

    /**
     * Registration attempts allowed per IP address within the decay window.
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Length of the rate limiting window, in seconds.
     */
    protected const DECAY_SECONDS = 600;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public bool $newsletter = false;

    #[Locked]
    public ?string $referrer = null;

    /**
     * Capture where the visitor came from, for the admin sign-up email.
     */
    public function mount(): void
    {
        $this->referrer = request()->headers->get('referer');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(AdminNotifier $adminNotifier): void
    {
        $this->throttleAttempt('register|'.request()->ip(), self::MAX_ATTEMPTS, self::DECAY_SECONDS, 'email');

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        $this->verifyTurnstile();

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        if ($this->newsletter) {
            NewsletterSubscriber::firstOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'subscribed_at' => now()]
            );
        }

        $adminNotifier->notifyOfSignUp($user, $this->referrer);

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
