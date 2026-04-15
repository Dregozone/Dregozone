<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        <style>
            body { font-family: 'Figtree', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-white text-stone-900 min-h-screen">

        <div class="min-h-screen flex">

            <!-- ===== Left decorative panel (lg+) ===== -->
            <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden bg-stone-950 flex-col">

                <!-- Decorative background blobs -->
                <div class="absolute inset-0 pointer-events-none select-none">
                    <!-- Top-right amber glow -->
                    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-500/20 blur-3xl animate-auth-pulse-slow"></div>
                    <!-- Bottom-left stone glow -->
                    <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-stone-700/40 blur-3xl animate-auth-pulse-slow" style="animation-delay:2s"></div>
                    <!-- Mid amber accent -->
                    <div class="absolute top-1/2 left-1/3 w-64 h-64 rounded-full bg-amber-600/10 blur-2xl animate-auth-pulse-slow" style="animation-delay:1s"></div>

                    <!-- Subtle grid / dot pattern -->
                    <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="auth-dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                                <circle cx="1.5" cy="1.5" r="1.5" fill="white"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#auth-dots)"/>
                    </svg>
                </div>

                <!-- Top: Brand -->
                <div class="relative z-10 p-10 xl:p-14">
                    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 group">
                        <span class="text-2xl font-black tracking-tight text-white">
                            <span class="text-amber-500 group-hover:text-amber-400 transition-colors duration-200">Anders</span>Learmonth
                        </span>
                    </a>
                </div>

                <!-- Centre: Floating card with quote / tagline -->
                <div class="relative z-10 flex-1 flex flex-col items-start justify-center px-10 xl:px-14 pb-10">

                    <!-- Floating graphic element -->
                    <div class="mb-10 animate-auth-float">
                        <div class="relative inline-flex">
                            <div class="w-20 h-20 rounded-2xl bg-amber-500/20 flex items-center justify-center border border-amber-500/30 backdrop-blur-sm shadow-xl shadow-amber-900/20">
                                <svg class="w-10 h-10 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber-500 animate-auth-pulse-slow"></div>
                        </div>
                    </div>

                    <h2 class="text-3xl xl:text-4xl font-black text-white leading-tight mb-4">
                        Building things<br>for the web.
                    </h2>
                    <p class="text-stone-400 text-base xl:text-lg leading-relaxed max-w-xs">
                        Developer, writer, explorer. Create a free account to get more from the site.
                    </p>

                    <!-- Feature pills -->
                    <div class="mt-8 flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-stone-800 border border-stone-700 text-stone-300 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            Track read posts
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-stone-800 border border-stone-700 text-stone-300 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            Members-only posts
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-stone-800 border border-stone-700 text-stone-300 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            Newsletter control
                        </span>
                    </div>
                </div>

                <!-- Bottom: Social links -->
                <div class="relative z-10 px-10 xl:px-14 pb-10 flex items-center gap-3">
                    <a href="https://github.com/Dregozone" target="_blank" rel="noopener noreferrer"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-stone-800 text-stone-400 hover:bg-amber-500 hover:text-white transition-all duration-200"
                        aria-label="GitHub">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/in/andreas-learmonth-982318a1/" target="_blank" rel="noopener noreferrer"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-stone-800 text-stone-400 hover:bg-amber-500 hover:text-white transition-all duration-200"
                        aria-label="LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                    <a href="https://bsky.app/profile/anderslearmonth.bsky.social" target="_blank" rel="noopener noreferrer"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-stone-800 text-stone-400 hover:bg-amber-500 hover:text-white transition-all duration-200"
                        aria-label="Bluesky">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 600 530">
                            <path d="M135.72 44.03C202.216 93.951 273.74 195.17 300 249.49c26.262-54.316 97.782-155.54 164.28-205.46C512.26 8.009 590-19.862 590 68.825c0 17.712-10.155 148.79-16.111 170.07-20.703 73.984-96.144 92.854-163.25 81.433 117.3 19.964 147.14 86.092 82.697 152.22-122.39 125.59-175.91-31.511-189.63-71.766-2.514-7.38-3.69-10.832-3.708-7.896-.017-2.936-1.193.516-3.707 7.896-13.714 40.255-67.233 197.36-189.63 71.766-64.444-66.128-34.605-132.26 82.697-152.22-67.108 11.421-142.55-7.45-163.25-81.433C20.15 217.615 10 86.536 10 68.825c0-88.687 77.742-60.816 125.72-24.795z"/>
                        </svg>
                    </a>
                    <span class="text-stone-600 text-xs ml-2">© {{ date('Y') }} Anders Learmonth</span>
                </div>
            </div>

            <!-- ===== Right form panel ===== -->
            <div class="w-full lg:w-[48%] flex flex-col min-h-screen">

                <!-- Mobile-only back link -->
                <div class="lg:hidden flex items-center justify-between px-6 pt-6 pb-2">
                    <a href="{{ route('home') }}" wire:navigate class="text-xl font-black tracking-tight text-stone-900">
                        <span class="text-amber-600">Anders</span>Learmonth
                    </a>
                    <a href="{{ route('home') }}" wire:navigate
                        class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>

                <!-- Form centred in remaining space -->
                <div class="flex-1 flex flex-col items-center justify-center px-6 py-10 sm:px-10">
                    <div class="w-full max-w-[380px] animate-auth-enter">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Desktop back link at bottom -->
                <div class="hidden lg:flex items-center justify-center pb-8">
                    <a href="{{ route('home') }}" wire:navigate
                        class="inline-flex items-center gap-1.5 text-sm text-stone-400 hover:text-stone-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to site
                    </a>
                </div>
            </div>

        </div>

        @fluxScripts
    </body>
</html>
