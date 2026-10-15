<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Record the authenticated user's last activity, at most once an hour.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            User::query()
                ->whereKey($user->getKey())
                ->where(function ($query) {
                    $query->whereNull('last_active_at')
                        ->orWhere('last_active_at', '<=', now()->subHour());
                })
                ->update(['last_active_at' => now()]);
        }

        return $next($request);
    }
}
