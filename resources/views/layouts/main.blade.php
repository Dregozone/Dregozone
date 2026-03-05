<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Anders Learmonth — @yield('title', 'Developer & Writer')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full font-sans antialiased bg-white text-stone-900">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-stone-100">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between h-16">

                <!-- Brand -->
                <a href="{{ route('home') }}" class="text-lg font-black tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                    <span class="text-amber-500">A</span>ndersLearmonth
                </a>

                <!-- Primary nav -->
                <nav class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Home</a>
                    <a href="{{ route('blog') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Blog</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Contact</a>
                </nav>

                <!-- Auth controls -->
                <div class="hidden sm:flex items-center gap-4">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Admin</a>
                        @endif

                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Dashboard</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-stone-400 hover:text-stone-600 transition-colors cursor-pointer">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-stone-900 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-stone-700 transition-colors">Register</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-stone-50 border-t border-stone-100">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                <!-- Brand col -->
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="text-xl font-black tracking-tight text-stone-900">
                        <span class="text-amber-500">A</span>ndersLearmonth
                    </a>
                    <p class="mt-3 text-stone-500 text-sm leading-relaxed max-w-xs">
                        Building things for the web, writing about what I learn, and exploring the world one language at a time.
                    </p>
                    <div class="mt-5 flex gap-3">
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-stone-200 text-stone-600 hover:bg-stone-900 hover:text-white transition-colors" aria-label="GitHub">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-stone-200 text-stone-600 hover:bg-stone-900 hover:text-white transition-colors" aria-label="LinkedIn">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
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
                        <li><a href="{{ route('contact') }}" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">Contact</a></li>
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
                <p class="text-sm text-stone-400">&copy; {{ date('Y') }} Anders Learmonth. All rights reserved.</p>
                <p class="text-sm text-stone-400">Built with Laravel &amp; Livewire</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
