<div class="flex flex-col gap-7">

    <!-- Header -->
    <div class="flex flex-col gap-1 animate-auth-enter auth-stagger-1">
        <h1 class="text-2xl font-black tracking-tight text-stone-900">Reset your password</h1>
        <p class="text-sm text-stone-500">Enter your email and we'll send you a reset link</p>
    </div>

    <!-- Session Status (success message) -->
    @if (session('status'))
        <div class="animate-auth-enter auth-stagger-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700 flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-5 animate-auth-enter auth-stagger-2">

        <!-- Email -->
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-semibold text-stone-700">Email address</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autofocus
                placeholder="you@example.com"
                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-900 placeholder-stone-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all duration-150"
            />
            @error('email')
                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 bg-stone-900 hover:bg-stone-700 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 active:scale-[0.98]"
            wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
            <span wire:loading.remove>Send reset link</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Sending…
            </span>
        </button>

    </form>

    <!-- Back to login -->
    <p class="text-center text-sm text-stone-500 animate-auth-enter auth-stagger-3">
        Remembered it?
        <a href="{{ route('login') }}" wire:navigate
            class="font-semibold text-amber-600 hover:text-amber-700 transition-colors">
            Back to sign in
        </a>
    </p>

</div>
