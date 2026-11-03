@props(['project', 'index' => 0])

@php
    $image = $project->uploadedImage?->base64_data ?? $project->image;
    $isComplete = $project->status === 'completed';
@endphp

<article {{ $attributes->merge(['class' => 'group aurora-glass flex flex-col overflow-hidden rounded-2xl']) }} data-spotlight data-reveal style="--i: {{ $index }}">
    <div class="aspect-video overflow-hidden border-b border-edge bg-night">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-[1.4s] ease-aurora group-hover:scale-[1.04]">
        @else
            <div class="flex h-full items-center justify-center bg-[radial-gradient(80%_80%_at_70%_20%,rgb(160_141_255/0.18),transparent),radial-gradient(60%_60%_at_20%_80%,rgb(63_230_192/0.12),transparent)]">
                <span class="font-mono text-xs text-dim">{{ Str::slug($project->title) }}/</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <div class="mb-4 flex items-center gap-2 font-mono text-[11px]">
            <span @class(['h-1.5 w-1.5 rounded-full', 'bg-glow-teal' => $isComplete, 'animate-pulse bg-glow-violet' => ! $isComplete])></span>
            <span class="text-mute">{{ str_replace('_', ' ', $project->status) }}</span>
            @if ($project->featured)
                <span class="ml-auto text-glow-ember">featured</span>
            @endif
        </div>

        <h3 class="text-xl font-medium tracking-[-0.02em]">{{ $project->title }}</h3>
        <p class="mt-3 flex-1 text-sm leading-relaxed text-mute">{{ $project->description }}</p>

        @if ($project->technologies)
            <p class="mt-6 font-mono text-[10px] text-dim">{{ implode(' / ', array_slice($project->technologies, 0, 5)) }}</p>
        @endif

        @if ($project->url || $project->github_url)
            <div class="mt-5 flex gap-5 font-mono text-xs">
                @if ($project->url)
                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-glow-cyan transition-colors hover:text-ink">open ↗</a>
                @endif
                @if ($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="text-glow-cyan transition-colors hover:text-ink">source ↗</a>
                @endif
            </div>
        @endif
    </div>
</article>
