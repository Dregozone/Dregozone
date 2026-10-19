<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\User;
use Closure;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Emails the admin (ADMIN_NOTIFY_EMAIL) about new sign-ups and contact messages.
 *
 * Emails are sent after the response and never fail the form that triggered them.
 * More than BURST_LIMIT of one kind within BURST_WINDOW_MINUTES becomes a single summary.
 */
class AdminNotifier
{
    public const BURST_LIMIT = 5;

    public const BURST_WINDOW_MINUTES = 10;

    public function notifyOfSignUp(User $user, ?string $referrer = null): void
    {
        $this->notify(
            'sign-ups',
            fn (): Mailable => new NewUserSignedUp($user, User::query()->count(), $referrer),
        );
    }

    public function notifyOfContactMessage(ContactMessage $contactMessage): void
    {
        $this->notify(
            'contact-messages',
            fn (): Mailable => new NewContactMessage($contactMessage),
            fn (): string => route('admin.contact-messages.index'),
        );
    }

    /**
     * @param  Closure(): Mailable  $makeMailable
     * @param  (Closure(): string)|null  $makeAdminUrl
     */
    protected function notify(string $kind, Closure $makeMailable, ?Closure $makeAdminUrl = null): void
    {
        $recipient = config('services.admin_notify.email');

        if (blank($recipient)) {
            return;
        }

        defer(function () use ($kind, $recipient, $makeMailable, $makeAdminUrl): void {
            try {
                $countInWindow = $this->countInBurstWindow($kind);

                if ($countInWindow <= self::BURST_LIMIT) {
                    Mail::to($recipient)->send($makeMailable());
                } elseif ($countInWindow === self::BURST_LIMIT + 1) {
                    Mail::to($recipient)->send(new AdminNotificationBurst(
                        description: str_replace('-', ' ', $kind),
                        limit: self::BURST_LIMIT,
                        windowMinutes: self::BURST_WINDOW_MINUTES,
                        adminUrl: $makeAdminUrl?->__invoke(),
                    ));
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        });
    }

    /**
     * Count this notification within the current burst window, which starts at the first one.
     */
    protected function countInBurstWindow(string $kind): int
    {
        $key = 'admin-notify:'.$kind;

        Cache::add($key, 0, now()->addMinutes(self::BURST_WINDOW_MINUTES));

        return (int) Cache::increment($key);
    }
}
