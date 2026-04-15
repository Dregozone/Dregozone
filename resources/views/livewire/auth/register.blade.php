<div class="flex flex-col gap-7">

    <!-- Header -->
    <div class="flex flex-col gap-1 animate-auth-enter auth-stagger-1">
        <h1 class="text-2xl font-black tracking-tight text-stone-900">Create an account</h1>
        <p class="text-sm text-stone-500">Join the community — it's completely free</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="animate-auth-enter auth-stagger-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="register" class="flex flex-col gap-5 animate-auth-enter auth-stagger-2">

        <!-- Name -->
        <div class="flex flex-col gap-1.5">
            <label for="name" class="text-sm font-semibold text-stone-700">Full name</label>
            <input
                wire:model="name"
                id="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Your name"
                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-900 placeholder-stone-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all duration-150"
            />
            @error('name')
                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-semibold text-stone-700">Email address</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-900 placeholder-stone-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all duration-150"
            />
            @error('email')
                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-sm font-semibold text-stone-700">Password</label>
            <div
                x-data="{ show: false }"
                class="relative"
            >
                <input
                    wire:model="password"
                    id="password"
                    :type="show ? 'text' : 'password'"
                    required
                    autocomplete="new-password"
                    placeholder="Choose a strong password"
                    class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-stone-200 bg-white text-stone-900 placeholder-stone-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all duration-150"
                />
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-stone-400 hover:text-stone-600 transition-colors"
                    :aria-label="show ? 'Hide password' : 'Show password'">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Newsletter opt-in -->
        <label class="flex items-start gap-2.5 cursor-pointer select-none">
            <input wire:model="newsletter" type="checkbox" id="newsletter"
                class="mt-0.5 w-4 h-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500/40 focus:ring-2 transition-colors cursor-pointer flex-shrink-0" />
            <span class="text-xs text-stone-500 leading-relaxed">
                Please also sign me up to the newsletter. I consent to the
                <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener noreferrer"
                    class="underline text-stone-600 hover:text-amber-600 transition-colors">privacy policy</a>.
            </span>
        </label>

        <!-- Submit -->
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 bg-stone-900 hover:bg-stone-700 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 active:scale-[0.98] mt-1"
            wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
            <span wire:loading.remove>Create account</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Creating account…
            </span>
        </button>

    </form>

    <!-- Login link -->
    <p class="text-center text-sm text-stone-500 animate-auth-enter auth-stagger-3">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate
            class="font-semibold text-amber-600 hover:text-amber-700 transition-colors">
            Sign in
        </a>
    </p>

</div>
