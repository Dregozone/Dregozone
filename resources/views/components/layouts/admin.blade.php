@props(['title' => 'Admin'])

@php
    $primaryNavigation = [
        [
            'label' => 'Blog Posts',
            'route' => 'admin.blog.index',
            'pattern' => 'admin.blog.*',
            'icon' => 'document-text',
        ],
        [
            'label' => 'Projects',
            'route' => 'admin.projects.index',
            'pattern' => 'admin.projects.*',
            'icon' => 'briefcase',
        ],
        [
            'label' => 'Tools',
            'route' => 'admin.tools.index',
            'pattern' => 'admin.tools.*',
            'icon' => 'wrench-screwdriver',
        ],
        [
            'label' => 'Contact Messages',
            'route' => 'admin.contact-messages.index',
            'pattern' => 'admin.contact-messages.*',
            'icon' => 'envelope',
        ],
        [
            'label' => 'Blog Engagement',
            'route' => 'admin.blog-engagement.index',
            'pattern' => 'admin.blog-engagement.*',
            'icon' => 'chart-bar',
        ],
    ];

    $secondaryNavigation = [
        [
            'label' => 'Newsletter',
            'route' => 'admin.newsletter-subscribers.index',
            'pattern' => 'admin.newsletter-subscribers.*',
            'icon' => 'user-group',
        ],
        [
            'label' => 'Images',
            'route' => 'admin.images.index',
            'pattern' => 'admin.images.*',
            'icon' => 'photo',
        ],
    ];

    $secondaryNavigationActive = collect($secondaryNavigation)->contains(
        fn (array $item): bool => request()->routeIs($item['pattern'])
    );
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin - {{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700"
            x-data="{ open: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center gap-4">
                    <div class="flex min-w-0 items-center gap-4">
                        <a href="{{ route('admin.blog.index') }}" wire:navigate class="flex items-center gap-4 min-w-0">
                            <span class="truncate text-2xl font-bold text-gray-900 dark:text-white">
                                <span class="text-amber-500">Anders</span>Learmonth
                            </span>
                            <span class="hidden rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300 lg:inline-flex">
                                Admin
                            </span>
                        </a>

                        <div class="hidden h-8 w-px bg-gray-200 dark:bg-gray-700 lg:block"></div>

                        <flux:navbar class="hidden lg:flex lg:items-center lg:gap-1">
                            @foreach ($primaryNavigation as $item)
                                <flux:tooltip :content="$item['label']" position="bottom">
                                    <flux:navbar.item
                                        class="h-10 [&>div>svg]:size-5"
                                        :href="route($item['route'])"
                                        :current="request()->routeIs($item['pattern'])"
                                        :label="$item['label']"
                                        icon="{{ $item['icon'] }}"
                                        wire:navigate
                                    />
                                </flux:tooltip>
                            @endforeach
                        </flux:navbar>
                    </div>

                    <div class="hidden flex-1 items-center justify-end gap-2 sm:flex">
                        <flux:dropdown class="max-lg:hidden" position="bottom" align="end">
                            <flux:button variant="{{ $secondaryNavigationActive ? 'primary' : 'ghost' }}" icon="ellipsis-horizontal">
                                More
                            </flux:button>

                            <flux:menu>
                                @foreach ($secondaryNavigation as $item)
                                    <flux:menu.item :href="route($item['route'])" icon="{{ $item['icon'] }}" wire:navigate>
                                        {{ $item['label'] }}
                                    </flux:menu.item>
                                @endforeach

                                <flux:menu.separator />

                                <flux:menu.item :href="route('home')" icon="arrow-top-right-on-square" wire:navigate>
                                    View Site
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>

                        <flux:tooltip content="View Site" position="bottom">
                            <flux:navbar.item
                                class="hidden h-10 xl:flex [&>div>svg]:size-5"
                                :href="route('home')"
                                icon="arrow-top-right-on-square"
                                label="View Site"
                                wire:navigate
                            />
                        </flux:tooltip>

                        <flux:dropdown position="bottom" align="end">
                            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

                            <flux:menu>
                                <flux:menu.radio.group>
                                    <div class="p-0 text-sm font-normal">
                                        <div class="flex items-center gap-3 px-1 py-1.5 text-start text-sm">
                                            <span class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-xl">
                                                <span class="flex h-full w-full items-center justify-center rounded-xl bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                                    {{ auth()->user()->initials() }}
                                                </span>
                                            </span>

                                            <div class="grid flex-1 text-start text-sm leading-tight">
                                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                                <span class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </flux:menu.radio.group>

                                <flux:menu.separator />

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                        Logout
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    </div>

                    <!-- Mobile hamburger button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                            aria-controls="mobile-menu" :aria-expanded="open">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon (shown when closed) -->
                            <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <!-- Close icon (shown when open) -->
                            <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="open" x-transition id="mobile-menu" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.blog.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.blog.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Blog Posts
                    </a>
                    <a href="{{ route('admin.projects.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.projects.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Projects
                    </a>
                    <a href="{{ route('admin.tools.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.tools.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Tools
                    </a>
                    <a href="{{ route('admin.contact-messages.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.contact-messages.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Contact Messages
                    </a>
                    <a href="{{ route('admin.blog-engagement.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.blog-engagement.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Blog Engagement
                    </a>
                    <a href="{{ route('admin.newsletter-subscribers.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.newsletter-subscribers.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Newsletter
                    </a>
                    {{-- <a href="{{ route('admin.image-converter') }}"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.image-converter') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Image Converter
                    </a> --}}
                    <a href="{{ route('admin.images.index') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 {{ request()->routeIs('admin.images.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200' }}">
                        Images
                    </a>
                    <a href="{{ route('home') }}"
                        @click="open = false"
                        class="block pl-3 pr-4 py-2 text-base font-medium border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200">
                        View Site
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center px-4 mb-3">
                        <span class="text-sm text-gray-500 dark:text-gray-300">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </div>
                    <div class="space-y-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    @fluxScripts
</body>

</html>
