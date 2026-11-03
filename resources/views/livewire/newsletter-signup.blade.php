<div>
    @if ($subscribed)
        <div class="flex items-start gap-4 rounded-2xl border border-glow-teal/30 bg-glow-teal/5 p-5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-glow-teal/15 text-glow-teal shadow-[0_0_24px_rgb(63_230_192/0.35)]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-medium text-ink">You're subscribed!</p>
                <p class="mt-1 text-sm text-mute">Thanks — I'll only send something worth opening.</p>
            </div>
        </div>
    @else
        <form wire:submit="subscribe" class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="newsletter-email" class="sr-only">Email address</label>
                    <input
                        type="email"
                        id="newsletter-email"
                        wire:model="email"
                        placeholder="your@email.com"
                        class="aurora-input py-3"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="aurora-button shrink-0 rounded-xl">
                    Subscribe
                </button>
            </div>
            @if($name !== null && $name !== '')
                <div>
                    <label for="newsletter-name" class="sr-only">Your name (optional)</label>
                    <input
                        type="text"
                        id="newsletter-name"
                        wire:model="name"
                        placeholder="Your name (optional)"
                        class="aurora-input py-3"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            @if (filled(config('services.turnstile.site_key')))
                <div>
                    <x-turnstile theme="dark" />
                    @error('turnstileToken')
                        <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            <p class="font-mono text-[11px] text-dim">No spam, ever. Unsubscribe any time.</p>
        </form>
    @endif
</div>
