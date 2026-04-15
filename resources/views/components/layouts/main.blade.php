@props(['title' => 'Developer & Writer'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Anders Learmonth — {{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full font-sans antialiased bg-white text-stone-900">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-stone-100"
        x-data="{ mobileOpen: false }">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between h-16">

                <!-- Brand -->
                <a href="{{ route('home') }}" class="text-lg font-black tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                    <span class="text-amber-600">Anders</span>Learmonth
                </a>

                <!-- Primary nav -->
                <nav class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Home</a>
                    <a href="{{ route('blog') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Blog</a>
                    <a href="{{ route('projects') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Projects</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Contact</a>
                </nav>

                <!-- Auth controls (desktop) -->
                <div class="hidden sm:flex items-center gap-4">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Admin</a>
                        @endif

                        <a href="{{ route('settings.profile') }}" class="text-stone-400 hover:text-stone-700 transition-colors" title="{{ __('Settings') }}" aria-label="{{ __('Settings') }}">
                            <flux:icon.cog-6-tooth class="w-5 h-5" />
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-stone-400 hover:text-stone-600 transition-colors cursor-pointer">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-stone-900 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-stone-700 transition-colors">Register</a>
                    @endauth
                </div>

                <!-- Mobile hamburger -->
                <button @click="mobileOpen = !mobileOpen"
                    class="sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition-colors"
                    aria-label="Toggle navigation">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="sm:hidden border-t border-stone-100 bg-white/95 backdrop-blur-md">
            <nav class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-1">
                <a href="{{ route('home') }}" @click="mobileOpen = false"
                    class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Home</a>
                <a href="{{ route('blog') }}" @click="mobileOpen = false"
                    class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Blog</a>
                <a href="{{ route('projects') }}" @click="mobileOpen = false"
                    class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Projects</a>
                <a href="{{ route('contact') }}" @click="mobileOpen = false"
                    class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Contact</a>

                <div class="mt-3 pt-3 border-t border-stone-100 flex flex-col gap-2">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" @click="mobileOpen = false"
                                class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Admin</a>
                        @endif
                        <a href="{{ route('settings.profile') }}" @click="mobileOpen = false"
                            class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium text-stone-400 hover:text-stone-600 hover:bg-stone-50 transition-colors cursor-pointer">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" @click="mobileOpen = false"
                            class="px-3 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">Login</a>
                        <a href="{{ route('register') }}" @click="mobileOpen = false"
                            class="mx-3 mt-1 text-center bg-stone-900 text-white px-4 py-2.5 rounded-full text-sm font-semibold hover:bg-stone-700 transition-colors">Register</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-stone-50 border-t border-stone-100">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                <!-- Brand col -->
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="text-xl font-black tracking-tight text-stone-900">
                        <span class="text-amber-600">Anders</span>Learmonth
                    </a>
                    <p class="mt-3 text-stone-500 text-sm leading-relaxed max-w-xs">
                        Building things for the web, writing about what I learn, and exploring the world one language at a time.
                    </p>
                    <div class="mt-5 flex gap-3">
                        <a href="https://github.com/Dregozone" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center rounded-full bg-stone-200 text-stone-600 hover:bg-stone-900 hover:text-white transition-colors" aria-label="GitHub">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/in/andreas-learmonth-982318a1/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center rounded-full bg-stone-200 text-stone-600 hover:bg-stone-900 hover:text-white transition-colors" aria-label="LinkedIn">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <a href="https://bsky.app/profile/anderslearmonth.bsky.social" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center rounded-full bg-stone-200 text-stone-600 hover:bg-stone-900 hover:text-white transition-colors" aria-label="Bluesky">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 600 530">
                                <path d="M135.72 44.03C202.216 93.951 273.74 195.17 300 249.49c26.262-54.316 97.782-155.54 164.28-205.46C512.26 8.009 590-19.862 590 68.825c0 17.712-10.155 148.79-16.111 170.07-20.703 73.984-96.144 92.854-163.25 81.433 117.3 19.964 147.14 86.092 82.697 152.22-122.39 125.59-175.91-31.511-189.63-71.766-2.514-7.38-3.69-10.832-3.708-7.896-.017-2.936-1.193.516-3.707 7.896-13.714 40.255-67.233 197.36-189.63 71.766-64.444-66.128-34.605-132.26 82.697-152.22-67.108 11.421-142.55-7.45-163.25-81.433C20.15 217.615 10 86.536 10 68.825c0-88.687 77.742-60.816 125.72-24.795z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Nav col -->
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-4">Pages</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Home</a></li>
                        <li><a href="{{ route('blog') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Blog</a></li>
                        <li><a href="{{ route('projects') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Projects</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Contact</a></li>
                        <li><a href="{{ route('privacy-policy') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact col -->
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-4">Say hi</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-stone-900 hover:text-amber-600 transition-colors">
                        Send a message
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>

            </div>

            <div class="mt-12 pt-8 border-t border-stone-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-stone-400">&copy; {{ date('Y') }} Anders Learmonth. All rights reserved. Blog posts and written content may not be reproduced without attribution.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy-policy') }}" class="text-sm text-stone-400 hover:text-stone-600 transition-colors">Privacy Policy</a>
                    <span class="text-stone-300">·</span>
                    <p class="text-sm text-stone-400">Built with Laravel &amp; Livewire</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cookie Consent Banner -->
    <x-cookie-banner />

    @fluxScripts
</body>

</html>
