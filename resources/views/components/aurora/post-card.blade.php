@props(['post', 'index' => 0, 'showViews' => false])

@php $isRead = auth()->check() && $post->relationLoaded('reads') && $post->reads->isNotEmpty(); @endphp

<article {{ $attributes->merge(['class' => 'group aurora-glass relative flex flex-col overflow-hidden rounded-2xl']) }} data-spotlight data-reveal style="--i: {{ $index }}">
    <div @class(['aspect-video overflow-hidden border-b border-edge bg-night', 'opacity-60' => $isRead])>
        @if ($post->image)
            <img src="{{ $post->image->base64_data }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-[1.4s] ease-aurora group-hover:scale-[1.04]">
        @else
            <div class="relative flex h-full items-end bg-[radial-gradient(90%_90%_at_80%_0%,rgb(160_141_255/0.2),transparent),radial-gradient(70%_70%_at_10%_100%,rgb(63_230_192/0.14),transparent)] p-5">
                <span class="font-mono text-xs text-dim">{{ $post->slug }}.md</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <div class="mb-4 flex flex-wrap items-center gap-2 font-mono text-[11px] text-dim">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('Y-m-d') }}</time>
            @if ($showViews)
                <span>· {{ number_format($post->views) }} views</span>
            @endif
            @if ($post->tags && count($post->tags) > 0)
                <span class="aurora-chip ml-auto">{{ $post->tags[0] }}</span>
            @endif
            @if ($isRead)
                <span class="aurora-chip border-glow-teal/30 text-glow-teal">✓ Read</span>
            @endif
        </div>

        <h3 @class(['text-lg font-medium leading-snug tracking-[-0.015em] transition-colors duration-300 group-hover:text-glow-cyan', 'text-mute' => $isRead])>
            <a href="{{ route('blog.post', $post) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
        </h3>

        @if ($post->excerpt)
            <p class="mt-3 flex-1 text-sm leading-relaxed text-mute">{{ Str::limit($post->excerpt, 140) }}</p>
        @endif

        <span class="mt-6 inline-flex items-center gap-2 font-mono text-xs text-dim transition-colors group-hover:text-glow-teal">
            read <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
        </span>
    </div>
</article>
