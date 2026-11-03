<div>
    <x-aurora.page-header :path="'~/blog/'.$post->slug" eyebrow="cat post.md" width="max-w-4xl" :description="$post->excerpt">
        <x-slot:title>{{ $post->title }}</x-slot:title>

        <div class="flex flex-col gap-6">
            <div class="flex flex-wrap items-center gap-3 font-mono text-xs text-dim">
                <a href="{{ route('blog') }}" class="text-mute transition-colors hover:text-glow-cyan">← cd ../blog</a>
                <span aria-hidden="true">·</span>
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j M Y') }}</time>
                <span aria-hidden="true">·</span>
                <span>{{ number_format($post->views) }} views</span>
                @if ($post->tags)
                    <span class="flex flex-wrap gap-1.5">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('blog', ['tag' => $tag]) }}" class="aurora-chip transition-colors hover:border-glow-teal/40 hover:text-ink">{{ $tag }}</a>
                        @endforeach
                    </span>
                @endif
            </div>

            @auth
                <div>
                    @if ($isRead)
                        <div class="inline-flex flex-wrap items-center gap-3 rounded-full border border-glow-teal/30 bg-glow-teal/5 px-4 py-2 text-sm">
                            <span class="text-glow-teal">✓ Previously read on {{ $readAt }}</span>
                            <button wire:click="markAsUnread" class="font-mono text-xs text-mute underline-offset-2 transition-colors hover:text-ink hover:underline">Mark as unread</button>
                        </div>
                    @else
                        <button wire:click="markAsRead" class="inline-flex items-center gap-2 rounded-full border border-edge-strong px-4 py-2 text-sm text-mute transition-colors hover:border-glow-teal/40 hover:text-glow-teal">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Mark as read
                        </button>
                    @endif
                </div>
            @endauth
        </div>
    </x-aurora.page-header>

    {{-- Reading progress: a thin line of aurora that fills as you read. --}}
    <div class="relative px-6 py-14 lg:py-20" data-progress>
        <div class="aurora-gradient pointer-events-none fixed inset-x-0 top-0 z-[60] h-0.5 origin-left" style="transform: scaleX(var(--p, 0))" aria-hidden="true"></div>

        <div class="mx-auto max-w-3xl">
            @if ($post->image)
                <figure class="mb-12 overflow-hidden rounded-2xl border border-edge" data-reveal>
                    <img src="{{ $post->image->base64_data }}" alt="{{ $post->title }}" loading="eager" fetchpriority="high" decoding="async" class="h-64 w-full object-cover md:h-96">
                </figure>
            @endif

            <article class="aurora-prose">
                {!! $post->content !!}
            </article>

            <div class="aurora-glass mt-16 flex flex-col gap-5 rounded-2xl p-6 sm:flex-row sm:items-center sm:justify-between"
                x-data="{ copied: false, copy() { navigator.clipboard.writeText(window.location.href); this.copied = true; setTimeout(() => this.copied = false, 2000) } }">
                <div>
                    <p class="font-medium text-ink">Share this post</p>
                    <p class="mt-1 text-sm text-mute">Help others discover this content</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener"
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-edge text-mute transition-colors hover:border-glow-teal/50 hover:text-glow-teal">
                        <span class="sr-only">Share on X</span>
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener"
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-edge text-mute transition-colors hover:border-glow-teal/50 hover:text-glow-teal">
                        <span class="sr-only">Share on LinkedIn</span>
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>
                    </a>
                    <button type="button" @click="copy()"
                        class="flex h-9 items-center gap-2 rounded-full border border-edge px-3 font-mono text-xs text-mute transition-colors hover:border-glow-teal/50 hover:text-glow-teal">
                        <span x-text="copied ? 'copied ✓' : 'copy link'">copy link</span>
                    </button>
                </div>
            </div>

            <p class="mt-6 font-mono text-[11px] leading-relaxed text-dim">
                &copy; {{ date('Y') }} Anders Learmonth. All rights reserved. This article is original content and may not be reproduced, copied, or republished without written permission. Please link back with attribution if you reference or quote this work.
            </p>
        </div>
    </div>

    @if ($relatedPosts->count() > 0)
        <section class="border-t border-edge px-6 py-20 lg:py-24">
            <div class="mx-auto max-w-6xl">
                <div class="mb-10" data-reveal>
                    <p class="aurora-eyebrow mb-4">// keep reading</p>
                    <h2 class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">Related posts</h2>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($relatedPosts as $relatedPost)
                        <x-aurora.post-card :post="$relatedPost" :index="$loop->index" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-aurora.newsletter eyebrow="// enjoyed this post?" heading="Get more like this in your inbox." description="No spam, ever. Just occasional updates when I publish something worth reading." />
</div>
