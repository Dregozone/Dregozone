<?php

namespace App\Livewire\Auth;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public bool $newsletter = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        if ($this->newsletter) {
            NewsletterSubscriber::firstOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'subscribed_at' => now()]
            );
        }

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
