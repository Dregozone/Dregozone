@props([
    'path',
    'eyebrow' => null,
    'description' => null,
    'width' => 'max-w-6xl',
])

{{--
    Inner-page header: the live aurora from the home hero, dimmed so content leads.
    The mono path doubles as a breadcrumb, echoing the terminal motif.
--}}
<section {{ $attributes->merge(['class' => 'relative -mt-16 overflow-hidden border-b border-edge pt-16']) }} data-plate-frame>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_80%_at_75%_0%,rgb(160_141_255/0.14),transparent_70%),radial-gradient(50%_70%_at_15%_10%,rgb(63_230_192/0.08),transparent_70%)]"></div>
    <canvas wire:ignore class="aurora-canvas pointer-events-none absolute inset-0 [--aurora-opacity:0.55] [mask-image:linear-gradient(to_bottom,#000_35%,transparent_92%)]" data-aurora data-baseline="0.5" data-envelope-start="0.3" data-envelope-end="0.85" aria-hidden="true"></canvas>
    <div class="aurora-grid pointer-events-none absolute inset-0 opacity-30 [mask-image:linear-gradient(to_bottom,#000,transparent_85%)]"></div>
    <div class="aurora-grain pointer-events-none absolute inset-0"></div>

    <div class="{{ $width }} relative mx-auto px-6 pb-16 pt-20 lg:pb-20 lg:pt-28">
        <p class="aurora-rise mb-6 font-mono text-sm text-mute" style="--delay: .1s">
            <span class="text-glow-teal">anders@lab</span><span class="text-dim">:</span><span class="text-glow-cyan">{{ $path }}</span><span class="text-dim">$</span>
            @if ($eyebrow)
                <span class="ml-1">{{ $eyebrow }}</span>
            @endif
        </p>

        <h1 class="aurora-rise max-w-4xl text-[clamp(2.4rem,5.6vw,4.75rem)] font-medium leading-[0.98] tracking-[-0.045em]" style="--delay: .25s">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="aurora-rise mt-6 max-w-2xl text-lg leading-relaxed text-mute" style="--delay: .4s">{{ $description }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="aurora-rise mt-10" style="--delay: .55s">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>
