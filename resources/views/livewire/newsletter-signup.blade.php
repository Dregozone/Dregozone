<div>
    @if ($subscribed)
        <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-green-800 dark:text-green-200">
                        Successfully Subscribed!
                    </h3>
                    <p class="text-green-700 dark:text-green-300">
                        Thank you for subscribing to my newsletter. You'll be the first to know about new projects and
                        insights!
                    </p>
                </div>
            </div>
        </div>
    @else
        <form wire:submit="subscribe" class="max-w-md mx-auto">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="email" class="sr-only">Email address</label>

                    <div class="w-full rounded-lg bg-gradient-to-tr from-violet-600 via-red-300 to-blue-500 p-0.5">
                        <input 
                            type="email" 
                            id="email" 
                            wire:model="email" 
                            placeholder="Enter your email address"
                            class="
                                w-full bg-white/80 px-4 py-3 rounded-md border-0 text-gray-900 placeholder-gray-500 
                                {{-- focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 --}} 
                                focus:outline-none focus:bg-white/70 transition-colors duration-200 focus:shadow-lg
                            "
                            required
                        >
                    </div>

                    @error('email')
                        <p class="mt-1 text-sm text-red-200">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                    Subscribe
                </button>
            </div>
            @if ($name)
                <div class="mt-4">
                    <label for="name" class="sr-only">Name (optional)</label>
                    <input type="text" id="name" wire:model="name" placeholder="Your name (optional)"
                        class="w-full px-4 py-2 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                    @error('name')
                        <p class="mt-1 text-sm text-red-200">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            <p class="mt-3 text-sm text-blue-100">
                No spam, unsubscribe at any time. I respect your privacy.
            </p>
        </form>
    @endif
</div>
