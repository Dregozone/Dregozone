<?php

namespace App\Livewire\Settings;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Newsletter')]
class Newsletter extends Component
{
    public string $subscriptionStatus = 'unsubscribed';

    public bool $saved = false;

    /**
     * Mount the component and load the user's current subscription status.
     */
    public function mount(): void
    {
        $subscriber = $this->getCurrentSubscriber();

        $this->subscriptionStatus = ($subscriber && $subscriber->is_subscribed) ? 'subscribed' : 'unsubscribed';
    }

    /**
     * Update the newsletter subscription status in real-time whenever the radio selection changes.
     */
    public function updatedSubscriptionStatus(string $value): void
    {
        $subscriber = $this->getCurrentSubscriber();

        if ($value === 'subscribed') {
            if ($subscriber) {
                $subscriber->update([
                    'is_subscribed' => true,
                    'subscribed_at' => $subscriber->subscribed_at ?? now(),
                    'unsubscribed_at' => null,
                ]);
            } else {
                NewsletterSubscriber::create([
                    'email' => Auth::user()->email,
                    'name' => Auth::user()->name,
                    'is_subscribed' => true,
                    'subscribed_at' => now(),
                ]);
            }
        } else {
            if ($subscriber) {
                $subscriber->unsubscribe();
            }
        }

        $this->saved = true;
    }

    /**
     * Retrieve the newsletter subscriber record for the currently authenticated user.
     */
    private function getCurrentSubscriber(): ?NewsletterSubscriber
    {
        return NewsletterSubscriber::where('email', Auth::user()->email)->first();
    }
}
