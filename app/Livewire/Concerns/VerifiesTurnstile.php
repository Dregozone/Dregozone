<?php

namespace App\Livewire\Concerns;

use App\Rules\Turnstile;
use Illuminate\Support\Facades\Validator;

/**
 * Adds a Cloudflare Turnstile check to a Livewire form that renders <x-turnstile />.
 */
trait VerifiesTurnstile
{
    public string $turnstileToken = '';

    /**
     * Checks the Turnstile token, then resets the widget, as Cloudflare accepts each token only once.
     */
    protected function verifyTurnstile(): void
    {
        $token = $this->turnstileToken;

        $this->reset('turnstileToken');
        $this->dispatch('turnstile-reset', id: $this->getId());

        Validator::make(
            ['turnstileToken' => $token],
            ['turnstileToken' => [new Turnstile]],
        )->validate();
    }
}
