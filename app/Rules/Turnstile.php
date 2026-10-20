<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Checks a Cloudflare Turnstile token with Cloudflare's siteverify API.
 *
 * Passes without a check when no TURNSTILE_SECRET_KEY is set, so local and test environments need no keys.
 */
class Turnstile implements ValidationRule
{
    public const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Indicates whether the rule should be implicit, so a missing token fails rather than being skipped.
     *
     * @var bool
     */
    public $implicit = true;

    public static function isEnabled(): bool
    {
        return filled(config('services.turnstile.secret_key'));
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isEnabled()) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the security check.');

            return;
        }

        try {
            $response = Http::asForm()->timeout(10)->post(self::VERIFY_URL, [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (ConnectionException $exception) {
            report($exception);

            $fail('The security check could not be completed. Please try again.');

            return;
        }

        if ($response->json('success') !== true) {
            $fail('The security check failed. Please try again.');
        }
    }
}
