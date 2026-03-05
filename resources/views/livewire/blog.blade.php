<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">
                    Blog & Insights
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Thoughts on web development, technology trends, and industry insights.
                    Sharing knowledge and experiences from the front lines of modern web development.
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live="search" placeholder="Search posts..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Tag Filter -->
                    <div class="relative">
                        <select wire:model.live="tag"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">All Tags</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag }}">{{ $tag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="relative">
                        <select wire:model.live="sortBy"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
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
    <div class="bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <article
                            class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            @if ($post->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $post->published_at->format('M j, Y') }}
                                    </span>
                                    @if ($post->tags)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                        <span class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ $post->tags[0] ?? '' }}
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $post->views }} views
                                    </span>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('blog.post', $post) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                        Read More →
                                    </a>
                                    @if ($post->tags)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($post->tags, 0, 2) as $tag)
                                                <span
                                                    class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 dark:text-gray-500 text-lg mb-4">
                        @if ($search || $tag)
                            No posts found matching your criteria.
                        @else
                            No blog posts yet. Check back soon!
                        @endif
                    </div>
                    @if ($search || $tag)
                        <button wire:click="$set('search', '')" wire:click="$set('tag', '')"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            Clear filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Newsletter Signup Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Never Miss an Update
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Subscribe to get notified about new blog posts, projects, and insights delivered straight to your inbox.
            </p>
            @livewire('newsletter-signup')
        </div>
    </div>
</div>

