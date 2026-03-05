<div>
    <!-- Article Header -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-6">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Blog
                </a>
            </div>

            <header class="text-center">
                <div class="flex items-center justify-center gap-2 mb-4">
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

                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    {{ $post->title }}
                </h1>

                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ $post->excerpt }}
                </p>

                @if ($post->tags)
                    <div class="flex flex-wrap justify-center gap-2 mt-6">
                        @foreach ($post->tags as $tag)
                            <span
                                class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </header>
        </div>
    </div>

    <!-- Article Content -->
    <div class="bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if ($post->featured_image)
                <div class="mb-8">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                        class="w-full h-64 md:h-96 object-cover rounded-lg">
                </div>
            @endif

            <article class="prose prose-lg dark:prose-invert max-w-none">
                {!! $post->content !!}
            </article>

            <!-- Share Section -->
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Share this post
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Help others discover this content
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                            target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="text-gray-400 hover:text-green-600 transition-colors duration-200">
                            <span class="sr-only">Copy link</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Posts -->
    @if ($relatedPosts->count() > 0)
        <div class="bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    Related Posts
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($relatedPosts as $relatedPost)
                        <article
                            class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            @if ($relatedPost->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $relatedPost->featured_image }}" alt="{{ $relatedPost->title }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $relatedPost->published_at->format('M j, Y') }}
                                    </span>
                                    @if ($relatedPost->tags)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                        <span class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ $relatedPost->tags[0] ?? '' }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                    {{ $relatedPost->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ $relatedPost->excerpt }}
                                </p>
                                <a href="{{ route('blog.post', $relatedPost) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                    Read More →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Newsletter Signup Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Enjoyed This Post?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Subscribe to get more insights like this delivered to your inbox. No spam, just quality content.
            </p>
            @livewire('newsletter-signup')
        </div>
    </div>
</div>

