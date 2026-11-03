@php
    $settingsNavigation = [
        ['label' => __('Profile'), 'route' => 'settings.profile'],
        ['label' => __('Password'), 'route' => 'settings.password'],
        ['label' => __('Newsletter'), 'route' => 'settings.newsletter'],
    ];
@endphp

<div class="mx-auto flex max-w-5xl items-start gap-10 px-6 pt-12 max-md:flex-col">
    <nav class="w-full md:sticky md:top-24 md:w-[220px]" aria-label="{{ __('Settings') }}">
        <ul class="flex gap-1 font-mono text-xs md:flex-col">
            @foreach ($settingsNavigation as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" @if ($isActive) aria-current="page" @endif
                        @class([
                            'flex items-center gap-2 rounded-lg px-3 py-2.5 transition-colors',
                            'bg-white/5 text-ink' => $isActive,
                            'text-mute hover:bg-white/5 hover:text-ink' => ! $isActive,
                        ])>
                        <span @class(['h-1.5 w-1.5 rounded-full', 'bg-glow-teal shadow-[0_0_8px_rgb(63_230_192/0.8)]' => $isActive, 'bg-edge-strong' => ! $isActive])></span>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="aurora-glass w-full flex-1 rounded-2xl p-6 sm:p-8">
        <h2 class="text-2xl font-medium tracking-[-0.03em]">{{ $heading ?? '' }}</h2>
        <p class="mt-1 text-sm text-mute">{{ $subheading ?? '' }}</p>

        <div class="mt-6 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
