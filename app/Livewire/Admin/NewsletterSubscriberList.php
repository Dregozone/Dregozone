<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Newsletter Subscribers')]
class NewsletterSubscriberList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\View\View
    {
        $threeMonthsAgo = now()->subMonths(3);

        $totalActive = NewsletterSubscriber::where('is_subscribed', true)->count();
        $totalInactive = NewsletterSubscriber::where('is_subscribed', false)->count();
        $total = $totalActive + $totalInactive;
        $newSubscribers = NewsletterSubscriber::where('is_subscribed', true)
            ->where('subscribed_at', '>=', $threeMonthsAgo)
            ->count();
        $recentUnsubscribed = NewsletterSubscriber::where('is_subscribed', false)
            ->where('unsubscribed_at', '>=', $threeMonthsAgo)
            ->count();
        $activePercentage = $total > 0 ? round(($totalActive / $total) * 100, 1) : 0;

        $query = NewsletterSubscriber::query();

        if ($this->search) {
            $query->where('email', 'like', '%' . $this->search . '%');
        }

        if ($this->status === 'subscribed') {
            $query->where('is_subscribed', true);
        } elseif ($this->status === 'unsubscribed') {
            $query->where('is_subscribed', false);
        }

        // Order by most recent activity: use unsubscribed_at when present, else subscribed_at
        $query->orderByRaw('COALESCE(unsubscribed_at, subscribed_at) DESC');

        $subscribers = $query->paginate(20);

        return view('livewire.admin.newsletter-subscriber-list', [
            'subscribers' => $subscribers,
            'totalActive' => $totalActive,
            'newSubscribers' => $newSubscribers,
            'activePercentage' => $activePercentage,
            'recentUnsubscribed' => $recentUnsubscribed,
        ]);
    }
}
