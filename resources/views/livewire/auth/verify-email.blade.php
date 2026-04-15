<div class="flex flex-col gap-7">

    <!-- Header -->
    <div class="flex flex-col gap-1 animate-auth-enter auth-stagger-1">
        <!-- Icon -->
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-2">
            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
        <h1 class="text-2xl font-black tracking-tight text-stone-900">Verify your email</h1>
        <p class="text-sm text-stone-500">
            We've sent a verification link to your email address. Click the link in the email to activate your account.
        </p>
    </div>

    <!-- Re-sent confirmation -->
    @if (session('status') == 'verification-link-sent')
        <div class="animate-auth-enter rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700 flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <!-- Actions -->
    <div class="flex flex-col gap-3 animate-auth-enter auth-stagger-2">
        <button
            wire:click="sendVerification"
            type="button"
            class="w-full flex items-center justify-center gap-2 bg-stone-900 hover:bg-stone-700 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 active:scale-[0.98]"
            wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
            <span wire:loading.remove wire:target="sendVerification">Resend verification email</span>
            <span wire:loading wire:target="sendVerification" class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Sending…
            </span>
        </button>

        <button
            wire:click="logout"
            type="button"
            class="w-full text-center text-sm font-medium text-stone-500 hover:text-stone-900 py-2 transition-colors">
            Log out and return later
        </button>
    </div>

</div>
