<div>
    <x-aurora.page-header path="~/blog" eyebrow="ls -t"
        description="Thoughts on building for the web, the local AI lab, and what I'm learning along the way.">
        <x-slot:title>Field notes, <span class="aurora-gradient-text">written down.</span></x-slot:title>
    </x-aurora.page-header>

    <section id="posts" class="scroll-mt-20 px-6 py-14 lg:py-20">
        <div class="mx-auto max-w-6xl">
            {{-- Filters read like a query, because that is what they are. --}}
            <div class="aurora-glass mb-12 flex flex-col gap-4 rounded-2xl p-4 lg:flex-row lg:items-center" data-reveal>
                <label class="relative flex-1">
                    <span class="sr-only">Search posts</span>
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center font-mono text-xs text-glow-teal" aria-hidden="true">grep</span>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="search posts…" class="aurora-input pl-14">
                </label>
                <div class="grid grid-cols-2 gap-3 lg:flex">
                    <label>
                        <span class="sr-only">Filter by tag</span>
                        <select wire:model.live="tag" class="aurora-input lg:w-48">
                            <option value="">All tags</option>
                            @foreach ($tags as $tagOption)
                                <option value="{{ $tagOption }}">{{ $tagOption }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span class="sr-only">Sort posts</span>
                        <select wire:model.live="sortBy" class="aurora-input lg:w-44">
                            <option value="latest">Latest first</option>
                            <option value="oldest">Oldest first</option>
                            <option value="popular">Most popular</option>
                        </select>
                    </label>
                </div>
            </div>

            <div wire:loading.class="opacity-50" class="transition-opacity duration-300">
                @if ($posts->count() > 0)
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts as $post)
                            <x-aurora.post-card :post="$post" :index="$loop->index % 3" show-views wire:key="post-{{ $post->id }}" />
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $posts->links('components.aurora.pagination') }}
                    </div>
                @else
                    <div class="aurora-glass rounded-2xl px-6 py-16 text-center">
                        <p class="font-mono text-sm text-dim">// 0 results</p>
                        <p class="mt-3 text-lg text-mute">
                            @if ($search || $tag)
                                No posts found matching your criteria.
                            @else
                                No blog posts yet. Check back soon!
                            @endif
                        </p>
                        @if ($search || $tag)
                            <button wire:click="$set('search', ''); $set('tag', '')" class="mt-6 font-mono text-xs text-glow-cyan transition-colors hover:text-ink">
                                clear filters ↺
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <x-aurora.newsletter heading="Get new posts in your inbox." description="No spam, ever. Just occasional updates when I publish something worth reading." />
</div>
