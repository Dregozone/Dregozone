<div>
    <x-aurora.page-header :path="'~/blog/'.$post->slug" eyebrow="cat post.md --preview" width="max-w-4xl" :description="$post->excerpt">
        <x-slot:title>{{ $post->title }}</x-slot:title>

        <div class="flex flex-col gap-6">
            {{-- Preview notice: makes it obvious this is the admin view, not the live post. --}}
            <div class="inline-flex w-fit flex-wrap items-center gap-3 rounded-full border border-glow-violet/40 bg-glow-violet/10 px-4 py-2 text-sm">
                <svg class="h-4 w-4 shrink-0 text-glow-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span class="font-mono text-xs uppercase tracking-widest text-glow-violet">Preview Mode</span>
                <span class="text-mute">{{ $post->status === 'published' ?'This is how the published post appears' : 'This blog post is not yet published' }}</span>
                <a href="{{ route('admin.blog.edit', $post->id) }}" class="font-mono text-xs text-ink underline-offset-2 transition-colors hover:text-glow-teal hover:underline">Back to Editor →</a>
            </div>

            <div class="flex flex-wrap items-center gap-3 font-mono text-xs text-dim">
                <a href="{{ route('admin.blog.index') }}" class="text-mute transition-colors hover:text-glow-cyan">← cd ../admin/blog</a>
                <span aria-hidden="true">·</span>
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j M Y') }}</time>
                @else
                    <span>Unpublished</span>
                @endif
                <span aria-hidden="true">·</span>
                <span>{{ number_format($post->views) }} views</span>
                @if ($post->tags)
                    <span class="flex flex-wrap gap-1.5">
                        @foreach ($post->tags as $tag)
                            <span class="aurora-chip">{{ $tag }}</span>
                        @endforeach
                    </span>
                @endif
            </div>
        </div>
    </x-aurora.page-header>

    <div class="relative px-6 py-14 lg:py-20">
        <div class="mx-auto max-w-3xl">
            @if ($post->image)
                <figure class="mb-12 overflow-hidden rounded-2xl border border-edge">
                    <img src="{{ $post->image->base64_data }}" alt="{{ $post->title }}" loading="eager" decoding="async" class="h-64 w-full object-cover md:h-96">
                </figure>
            @endif

            <article class="aurora-prose">
                {!! $post->content !!}
            </article>
        </div>
    </div>

    {{-- Keep the way back to the editor in reach while reading a long draft. --}}
    <a href="{{ route('admin.blog.edit', $post->id) }}"
        class="aurora-glass fixed bottom-6 right-6 z-50 inline-flex items-center gap-2 rounded-full border border-glow-violet/40 px-4 py-2 font-mono text-xs text-ink shadow-lg transition-colors hover:border-glow-teal/50 hover:text-glow-teal">
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Back to Editor
    </a>
</div>
