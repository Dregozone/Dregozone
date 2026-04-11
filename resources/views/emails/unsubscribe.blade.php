<x-layouts.main title="Unsubscribe">
    <div class="min-h-screen flex items-center justify-center py-16 px-6">
        <div class="max-w-md w-full text-center">
            @if ($success)
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-stone-900 mb-3">Unsubscribed</h1>
                <p class="text-stone-600">{{ $message }}</p>
            @else
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-stone-900 mb-3">Invalid Link</h1>
                <p class="text-stone-600">{{ $message }}</p>
            @endif

            <a href="{{ route('home') }}"
                class="inline-block mt-8 text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                &larr; Back to home
            </a>
        </div>
    </div>
</x-layouts.main>
