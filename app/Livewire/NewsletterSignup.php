<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSignup extends Component
{
    public $email = '';

    public $name = '';

    public $subscribed = false;

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
        'name' => 'nullable|min:2|max:255',
    ];

    public function subscribe()
    {
        $this->validate();

        NewsletterSubscriber::create([
            'email' => $this->email,
            'name' => $this->name,
            'subscribed_at' => now(),
        ]);

        $this->subscribed = true;
        $this->reset(['email', 'name']);
    }

    public function render()
    {
        return view('livewire.newsletter-signup');
    }
}
