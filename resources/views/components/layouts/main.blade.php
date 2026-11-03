@props([
    'title' => 'Developer & AI Tinkerer',
    'description' => 'Anders Learmonth — senior full-stack engineer building Laravel products, local AI tooling and interfaces that feel effortless.',
    'socialImage' => null,
    'socialImageAlt' => null,
    'socialType' => 'website',
    'publishedAt' => null,
])

@php
    $navigation = [
        ['label' => 'Home', 'route' => 'home', 'active' => 'home'],
        ['label' => 'Now', 'route' => 'now', 'active' => 'now'],
        ['label' => 'Blog', 'route' => 'blog', 'active' => 'blog*'],
        ['label' => 'Projects', 'route' => 'projects', 'active' => 'projects'],
        ['label' => 'Tools', 'route' => 'tools', 'active' => ['tools', 'run-tools']],
        ['label' => 'Contact', 'route' => 'contact', 'active' => 'contact'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-aurora dark h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description }}">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#04060c">
    <title>Anders Learmonth — {{ $title }}</title>
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Link previews: a post's own header image when it has a suitable one, otherwise the site card. --}}
    @php
        $socialTitle = $socialType === 'article' ? $title : "Anders Learmonth — {$title}";
        $socialImageUrl = $socialImage ?? asset('images/social/card.jpg');
        $socialImageAlt ??= 'Anders Learmonth — Developer & AI Tinkerer, over an aurora above a mountain lake.';
    @endphp
    <meta property="og:site_name" content="Anders Learmonth">
    <meta property="og:type" content="{{ $socialType }}">
    <meta property="og:title" content="{{ $socialTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $socialImageUrl }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $socialImageAlt }}">
    @if ($publishedAt)
        <meta property="article:published_time" content="{{ $publishedAt }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $socialTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $socialImageUrl }}">
    <meta name="twitter:image:alt" content="{{ $socialImageAlt }}">
    <link rel="manifest" href="/manifest.webmanifest">
    <script>document.documentElement.classList.add('js-motion')</script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/motion.js'])
    @livewireStyles
    @if (filled(config('services.turnstile.site_key')))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>
    @endif
</head>

<body class="min-h-full bg-void font-display text-ink antialiased selection:bg-glow-teal/30 selection:text-white">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-ink focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-void">
        Skip to main content
    </a>

    {{-- Header: transparent over the sky, condenses into glass once you scroll. --}}
    <header data-site-header x-data="{ mobileOpen: false }"
        class="sticky top-0 z-50 border-b border-transparent transition-[background-color,border-color,backdrop-filter] duration-500 [&.is-scrolled]:border-edge [&.is-scrolled]:bg-void/70 [&.is-scrolled]:backdrop-blur-xl"
        :class="mobileOpen && 'bg-void/95 border-edge'">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-[-0.02em] text-ink">
                    <span class="aurora-gradient-text">Anders</span>Learmonth
                </a>

                <nav class="hidden items-center gap-7 sm:flex" aria-label="Primary">
                    @foreach ($navigation as $item)
                        @php $isActive = request()->routeIs($item['active']); @endphp
                        <a href="{{ route($item['route']) }}" @if ($isActive) aria-current="page" @endif
                            @class([
                                'relative text-sm transition-colors duration-300',
                                'text-ink' => $isActive,
                                'text-mute hover:text-ink' => ! $isActive,
                            ])>
                            {{ $item['label'] }}
                            @if ($isActive)
                                <span class="absolute -bottom-2 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-glow-teal shadow-[0_0_8px_2px_rgb(63_230_192/0.6)]" aria-hidden="true"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="hidden items-center gap-4 sm:flex">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" class="text-sm text-mute transition-colors hover:text-ink">Admin</a>
                        @endif

                        <a href="{{ route('settings.profile') }}" class="text-mute transition-colors hover:text-ink" title="{{ __('Settings') }}" aria-label="{{ __('Settings') }}">
                            <flux:icon.cog-6-tooth class="h-5 w-5" />
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="cursor-pointer text-sm text-mute transition-colors hover:text-ink">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-mute transition-colors hover:text-ink">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full border border-edge-strong bg-white/5 px-4 py-2 text-sm font-medium text-ink transition-colors hover:border-glow-teal/50">Register</a>
                    @endauth
                </div>

                <button @click="mobileOpen = !mobileOpen"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-mute transition-colors hover:bg-white/5 hover:text-ink sm:hidden"
                    aria-label="Toggle navigation" aria-controls="mobile-menu" :aria-expanded="mobileOpen.toString()">
                    <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg>
                    <svg x-show="mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
            class="border-t border-edge sm:hidden">
            <nav class="mx-auto flex max-w-6xl flex-col gap-1 px-6 py-4" aria-label="Mobile">
                @foreach ($navigation as $item)
                    @php $isActive = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}" @click="mobileOpen = false"
                        @class([
                            'flex items-center justify-between rounded-lg px-3 py-2.5 text-sm transition-colors',
                            'bg-white/5 text-ink' => $isActive,
                            'text-mute hover:bg-white/5 hover:text-ink' => ! $isActive,
                        ])>
                        {{ $item['label'] }}
                        @if ($isActive)
                            <span class="h-1.5 w-1.5 rounded-full bg-glow-teal" aria-hidden="true"></span>
                        @endif
                    </a>
                @endforeach

                <div class="mt-3 flex flex-col gap-2 border-t border-edge pt-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" class="rounded-lg px-3 py-2.5 text-sm text-mute hover:bg-white/5 hover:text-ink">Admin</a>
                        @endif
                        <a href="{{ route('settings.profile') }}" class="rounded-lg px-3 py-2.5 text-sm text-mute hover:bg-white/5 hover:text-ink">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full cursor-pointer rounded-lg px-3 py-2.5 text-left text-sm text-mute hover:bg-white/5 hover:text-ink">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2.5 text-sm text-mute hover:bg-white/5 hover:text-ink">Login</a>
                        <a href="{{ route('register') }}" class="mx-3 mt-1 rounded-full border border-edge-strong bg-white/5 px-4 py-2.5 text-center text-sm font-medium text-ink">Register</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content">
        {{ $slot }}
    </main>

    <footer class="relative overflow-hidden border-t border-edge">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-glow-teal/40 to-transparent"></div>
        <div class="mx-auto max-w-6xl px-6 pb-10 pt-16">
            <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr]">
                <div>
                    <a href="{{ route('home') }}" class="text-xl font-semibold tracking-[-0.02em] text-ink">
                        <span class="aurora-gradient-text">Anders</span>Learmonth
                    </a>
                    <p class="mt-3 max-w-xs text-sm leading-relaxed text-mute">
                        Building for the web, taking new AI tools apart to see what they can really do, and running a small lab on one desk.
                    </p>
                    <x-aurora.social-links class="mt-6" />
                </div>

                <div>
                    <p class="mb-4 font-mono text-xs text-dim">// pages</p>
                    <ul class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        @foreach ($navigation as $item)
                            <li><a href="{{ route($item['route']) }}" class="text-mute transition-colors hover:text-ink">{{ $item['label'] }}</a></li>
                        @endforeach
                        <li><a href="{{ route('privacy-policy') }}" class="text-mute transition-colors hover:text-ink">Privacy Policy</a></li>
                    </ul>
                </div>

                <div>
                    <p class="mb-4 font-mono text-xs text-dim">// say hi</p>
                    <a href="{{ route('contact') }}" class="group inline-flex items-center gap-2 text-sm font-medium text-ink">
                        Send a message
                        <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
                    </a>
                    <p class="mt-3 text-sm leading-relaxed text-mute">Projects, roles, or a problem worth talking through.</p>
                </div>
            </div>

            <div class="mt-14 flex flex-col gap-4 border-t border-edge pt-8 text-xs text-dim lg:flex-row lg:items-center lg:justify-between">
                <p>&copy; {{ date('Y') }} Anders Learmonth. All rights reserved. Blog posts and written content may not be reproduced without attribution.</p>
                <p class="font-mono">
                    // Laravel {{ app()->version() }} &amp; Livewire
                    @if (defined('LARAVEL_START'))
                        · rendered in {{ max(1, (int) round((microtime(true) - LARAVEL_START) * 1000)) }} ms
                    @endif
                </p>
            </div>
        </div>
    </footer>

    <x-cookie-banner />

    @fluxScripts
</body>

</html>
