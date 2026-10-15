<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Login;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DailyStatsController extends Controller
{
    private const TIMEZONE = 'Europe/London';

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $day = isset($validated['date'])
            ? Carbon::createFromFormat('Y-m-d', $validated['date'], self::TIMEZONE)->startOfDay()
            : Carbon::now(self::TIMEZONE)->subDay()->startOfDay();

        $start = $day->copy()->utc();
        $end = $day->copy()->endOfDay()->utc();

        $logins = Login::query()->whereBetween('created_at', [$start, $end]);

        return response()->json([
            'app' => 'Dregozone',
            'date' => $day->toDateString(),
            'signups' => User::query()->whereBetween('created_at', [$start, $end])->count(),
            'logins' => [
                'total' => (clone $logins)->count(),
                'unique' => (clone $logins)->distinct()->count('user_id'),
            ],
            'users' => [
                'total' => User::query()->count(),
                'active_7d' => User::query()->where('last_active_at', '>=', now()->subDays(7))->count(),
                'active_30d' => User::query()->where('last_active_at', '>=', now()->subDays(30))->count(),
            ],
            'newsletter_signups' => NewsletterSubscriber::query()
                ->whereBetween('created_at', [$start, $end])
                ->whereNull('unsubscribed_at')
                ->where('is_subscribed', true)
                ->count(),
            'contact_messages' => ContactMessage::query()->whereBetween('created_at', [$start, $end])->count(),
        ]);
    }
}
