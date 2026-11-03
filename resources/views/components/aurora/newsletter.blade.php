@props([
    'eyebrow' => '// subscribe',
    'heading' => 'Release notes from the lab.',
    'description' => "New experiments, projects and field notes, only when they're worth opening.",
])

<section {{ $attributes->merge(['class' => 'relative overflow-hidden border-t border-edge px-6 py-24 lg:py-28']) }}>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(50%_80%_at_85%_100%,rgb(160_141_255/0.12),transparent_70%),radial-gradient(40%_60%_at_10%_100%,rgb(63_230_192/0.08),transparent_70%)]"></div>
    <div class="relative mx-auto grid max-w-6xl gap-10 lg:grid-cols-[1.1fr_1fr] lg:items-center">
        <div data-reveal>
            <p class="aurora-eyebrow mb-4">{{ $eyebrow }}</p>
            <h2 class="text-[clamp(1.9rem,3.6vw,3rem)] font-medium leading-[1.02] tracking-[-0.035em]">{{ $heading }}</h2>
            <p class="mt-4 max-w-md leading-relaxed text-mute">{{ $description }}</p>
        </div>
        <div class="aurora-glass rounded-2xl p-6 sm:p-7" data-reveal style="--i: 1">
            @livewire('newsletter-signup')
        </div>
    </div>
</section>
