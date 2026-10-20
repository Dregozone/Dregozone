@props(['theme' => 'auto'])

{{-- Cloudflare Turnstile widget for a Livewire form using VerifiesTurnstile; render it only when TURNSTILE_SITE_KEY is set. The layout loads the Turnstile script. --}}

<div
    wire:ignore
    x-data="{
        widgetId: null,
        init() {
            if (! window.turnstile) {
                setTimeout(() => this.init(), 100);

                return;
            }

            this.widgetId = window.turnstile.render(this.$refs.widget, {
                sitekey: @js(config('services.turnstile.site_key')),
                theme: @js($theme),
                size: 'flexible',
                callback: (token) => $wire.turnstileToken = token,
                'expired-callback': () => $wire.turnstileToken = '',
                'error-callback': () => $wire.turnstileToken = '',
            });
        },
        destroy() {
            if (this.widgetId !== null) {
                window.turnstile.remove(this.widgetId);
            }
        },
    }"
    x-on:turnstile-reset.window="if ($event.detail.id === $wire.$id && widgetId !== null) { window.turnstile.reset(widgetId) }"
    {{ $attributes }}
>
    <div x-ref="widget"></div>
</div>
