<div
    x-data="cookieConsent()"
    x-show="!accepted"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    x-cloak
    class="fixed bottom-0 inset-x-0 z-50 p-4 sm:p-6"
    role="region"
    aria-label="Cookie consent"
>
    <div class="max-w-4xl mx-auto bg-stone-900 text-white rounded-2xl shadow-2xl border border-stone-700 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 px-6 py-5">
        <!-- Icon -->
        <div class="shrink-0 hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-amber-400/10 text-amber-400">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        <!-- Text -->
        <div class="flex-1 min-w-0">
            <p class="text-sm text-stone-300 leading-relaxed">
                This site uses essential cookies to keep you securely logged in and to protect against CSRF attacks.
                No tracking or advertising cookies are used.
                By continuing to use this site you consent to these essential cookies.
                Read our
                <a href="{{ route('privacy-policy') }}" class="text-amber-400 hover:text-amber-300 underline underline-offset-2 transition-colors">Privacy Policy</a>
                for full details.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('privacy-policy') }}"
               class="text-xs font-semibold text-stone-400 hover:text-white transition-colors whitespace-nowrap">
                Learn more
            </a>
            <button
                @click="accept()"
                type="button"
                class="px-5 py-2.5 bg-amber-400 hover:bg-amber-300 text-stone-900 text-sm font-bold rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-stone-900 whitespace-nowrap cursor-pointer">
                Accept &amp; Close
            </button>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            accepted: localStorage.getItem('cookie_consent') === 'accepted',
            accept() {
                localStorage.setItem('cookie_consent', 'accepted');
                this.accepted = true;
            },
        };
    }
</script>
