<div>
    @if ($subscribed)
        <div class="flex items-start gap-4 bg-stone-800 rounded-2xl p-6 border border-stone-700">
            <div class="shrink-0 w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-white">You're subscribed!</p>
                <p class="mt-1 text-sm text-stone-400">Thanks — I'll only send something worth opening.</p>
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
                        class="w-full bg-stone-800 border border-stone-700 text-white placeholder-stone-500 px-4 py-3 rounded-xl text-sm focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="shrink-0 bg-amber-400 text-amber-900 px-6 py-3 rounded-xl text-sm font-bold hover:bg-amber-300 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-stone-900">
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
                        class="w-full bg-stone-800 border border-stone-700 text-white placeholder-stone-500 px-4 py-3 rounded-xl text-sm focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            <p class="text-xs text-stone-500">No spam, ever. Unsubscribe any time.</p>
        </form>
    @endif
</div>
