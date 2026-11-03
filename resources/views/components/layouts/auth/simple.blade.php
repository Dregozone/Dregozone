<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-aurora dark h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#04060c">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <script>document.documentElement.classList.add('js-motion')</script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600|jetbrains-mono:400,500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/motion.js'])
    </head>
    <body class="min-h-screen bg-void font-display text-ink antialiased selection:bg-glow-teal/30">
        <div class="flex min-h-screen">
            {{-- The same sky as the home page, so signing in still feels like the site. --}}
            <div class="relative hidden overflow-hidden border-r border-edge lg:flex lg:w-[52%] lg:flex-col" data-plate-frame>
                <div class="absolute inset-0">
                    <img src="{{ asset('images/home/hero-aurora.webp') }}" srcset="{{ asset('images/home/hero-aurora-960.webp') }} 960w, {{ asset('images/home/hero-aurora.webp') }} 1536w" sizes="52vw"
                        width="1536" height="864" alt="" decoding="async" data-plate class="aurora-plate h-full w-full object-cover">
                    <canvas wire:ignore class="aurora-canvas absolute inset-0" data-aurora data-horizon="0.725" data-envelope-start="0" data-envelope-end="0.6" aria-hidden="true"></canvas>
                </div>
                <div class="aurora-grain pointer-events-none absolute inset-0"></div>
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-void via-void/30 to-void/60"></div>

                <div class="relative z-10 p-10 xl:p-14">
                    <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-[-0.02em] text-ink">
                        <span class="aurora-gradient-text">Anders</span>Learmonth
                    </a>
                </div>

                <div class="relative z-10 flex flex-1 flex-col justify-end px-10 pb-10 xl:px-14">
                    <p class="aurora-rise mb-6 font-mono text-sm text-mute" style="--delay: .2s">
                        <span class="text-glow-teal">guest@lab</span><span class="text-dim">:</span><span class="text-glow-cyan">~</span><span class="text-dim">$</span> ./join
                        <span class="aurora-caret ml-1" aria-hidden="true"></span>
                    </p>
                    <h2 class="aurora-rise max-w-md text-4xl font-medium leading-[1] tracking-[-0.04em] xl:text-5xl" style="--delay: .35s">
                        Building things <span class="aurora-gradient-text">for the web.</span>
                    </h2>
                    <p class="aurora-rise mt-5 max-w-sm leading-relaxed text-mute" style="--delay: .5s">
                        Developer, tinkerer, explorer. Create a free account to get more from the site.
                    </p>

                    <ul class="aurora-rise mt-8 flex flex-wrap gap-2" style="--delay: .65s">
                        @foreach (['Track read posts', 'Members-only posts', 'Newsletter control'] as $perk)
                            <li class="aurora-glass flex items-center gap-2 rounded-full px-3 py-1.5 text-xs text-ink">
                                <span class="h-1.5 w-1.5 rounded-full bg-glow-teal"></span>{{ $perk }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-12 flex items-center gap-4">
                        <x-aurora.social-links />
                        <span class="font-mono text-[11px] text-dim">© {{ date('Y') }} Anders Learmonth</span>
                    </div>
                </div>
            </div>

            <div class="relative flex min-h-screen w-full flex-col lg:w-[48%]">
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_50%_at_80%_0%,rgb(160_141_255/0.1),transparent_70%)]"></div>

                <div class="relative flex items-center justify-between px-6 pb-2 pt-6 lg:hidden">
                    <a href="{{ route('home') }}" class="text-xl font-semibold tracking-[-0.02em] text-ink">
                        <span class="aurora-gradient-text">Anders</span>Learmonth
                    </a>
                    <a href="{{ route('home') }}" class="font-mono text-xs text-mute transition-colors hover:text-ink">← back</a>
                </div>

                <div class="relative flex flex-1 flex-col items-center justify-center px-6 py-10 sm:px-10">
                    <div class="w-full max-w-[380px] animate-auth-enter">
                        {{ $slot }}
                    </div>
                </div>

                <div class="relative hidden items-center justify-center pb-8 lg:flex">
                    <a href="{{ route('home') }}" class="font-mono text-xs text-dim transition-colors hover:text-ink">← cd ~/ (back to site)</a>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
