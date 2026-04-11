<div>
    <!-- Hero Section -->
    <section class="bg-stone-50 border-b border-stone-100 py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-5">
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">From the blog</span>
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
            </div>
            <h1 class="text-4xl font-black text-stone-900 tracking-tight mb-4">Blog & Insights</h1>
            <p class="text-lg text-stone-600 max-w-2xl mx-auto">
                Thoughts on web development, technology trends, and industry insights.
                Sharing knowledge and experiences from the front lines of modern web development.
            </p>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <div class="bg-white border-b border-stone-100">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live="search" placeholder="Search posts..."
                            class="block w-full pl-10 pr-3 py-2 border border-stone-200 rounded-full leading-5 bg-stone-50 text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Tag Filter -->
                    <div class="relative">
                        <select wire:model.live="tag"
                            class="block w-full pl-3 pr-10 py-2 text-sm border border-stone-200 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 rounded-full bg-stone-50 text-stone-700 transition-colors">
                            <option value="">All Tags</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag }}">{{ $tag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="relative">
                        <select wire:model.live="sortBy"
                            class="block w-full pl-3 pr-10 py-2 text-sm border border-stone-200 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 rounded-full bg-stone-50 text-stone-700 transition-colors">
                            <option value="latest">Latest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="bg-stone-50">
        <div class="max-w-6xl mx-auto px-6 py-12">
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($posts as $post)
                        <article class="group bg-white rounded-2xl overflow-hidden border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all flex flex-col">
                            @if ($post->uploadedImage)
                                <div class="aspect-video overflow-hidden bg-stone-100">
                                    <img src="{{ $post->uploadedImage->base64_data }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-stone-100 to-stone-200 flex items-center justify-center">
                                    <span class="text-4xl text-stone-300 font-black">{{ mb_substr($post->title, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center gap-2.5 mb-3">
                                    @if ($post->tags)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            {{ $post->tags[0] ?? '' }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-stone-400">{{ $post->published_at->format('M j, Y') }}</span>
                                    <span class="text-xs text-stone-400">· {{ $post->views }} views</span>
                                </div>
                                <h3 class="font-bold text-stone-900 leading-snug mb-2 group-hover:text-amber-700 transition-colors">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-stone-500 text-sm leading-relaxed mb-4 flex-1">
                                    {{ $post->excerpt }}
                                </p>
                                <a href="{{ route('blog.post', $post) }}"
                                    class="mt-auto inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-amber-600 transition-colors">
                                    Read more
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-stone-400 text-lg mb-4">
                        @if ($search || $tag)
                            No posts found matching your criteria.
                        @else
                            No blog posts yet. Check back soon!
                        @endif
                    </p>
                    @if ($search || $tag)
                        <button wire:click="$set('search', '')"
                            class="text-sm font-bold text-stone-600 hover:text-amber-600 transition-colors">
                            Clear filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Newsletter Signup Section -->
    <section class="bg-stone-900 py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-3">Stay in the loop</p>
                    <h2 class="text-3xl font-black text-white tracking-tight mb-4">
                        Get new posts<br>in your inbox
                    </h2>
                    <p class="text-stone-400 leading-relaxed">
                        No spam, ever. Just occasional updates when I publish something worth reading.
                    </p>
                </div>
                <div>
                    @livewire('newsletter-signup')
                </div>
            </div>
        </div>
    </section>
</div>

