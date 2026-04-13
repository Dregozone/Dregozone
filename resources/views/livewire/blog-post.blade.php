<div>
    <!-- Article Header -->
    <section class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="mb-8">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold text-stone-500 hover:text-amber-600 transition-colors uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Blog
                </a>
            </div>

            <header class="text-center">
                <div class="flex items-center justify-center gap-2.5 mb-5">
                    @if ($post->tags)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                            {{ $post->tags[0] ?? '' }}
                        </span>
                    @endif
                    <span class="text-xs text-stone-400">{{ $post->published_at->format('M j, Y') }}</span>
                    <span class="text-xs text-stone-400">· {{ $post->views }} views</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-stone-900 tracking-tight mb-5">
                    {{ $post->title }}
                </h1>

                <p class="text-lg text-stone-600 max-w-2xl mx-auto leading-relaxed">
                    {{ $post->excerpt }}
                </p>

                @if ($post->tags)
                    <div class="flex flex-wrap justify-center gap-2 mt-6">
                        @foreach ($post->tags as $tag)
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </header>
        </div>
    </section>

    <!-- Article Content -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-6 py-12">
            @if ($post->image)
                <div class="mb-8">
                    <img src="{{ $post->image->base64_data }}" alt="{{ $post->title }}"
                        class="w-full h-64 md:h-96 object-cover rounded-2xl">
                </div>
            @endif

            <article class="prose prose-lg prose-stone max-w-none tracking-wider text-lg font-['Inter',sans-serif]">
                {!! $post->content !!}
            </article>

            <!-- Share Section -->
            <div class="mt-12 pt-8 border-t border-stone-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-stone-900 mb-1">Share this post</h3>
                        <p class="text-sm text-stone-500">Help others discover this content</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                            target="_blank" class="text-stone-400 hover:text-amber-600 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" class="text-stone-400 hover:text-amber-600 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="text-stone-400 hover:text-amber-600 transition-colors">
                            <span class="sr-only">Copy link</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Copyright Notice -->
            <div class="mt-6 p-4 bg-stone-50 rounded-xl border border-stone-100">
                <p class="text-xs text-stone-500 leading-relaxed">
                    &copy; {{ date('Y') }} Anders Learmonth. All rights reserved. This article is original content and may not be reproduced, copied, or republished without written permission. Please link back with attribution if you reference or quote this work.
                </p>
            </div>
        </div>
    </div>

    <!-- Related Posts -->
    @if ($relatedPosts->count() > 0)
        <section class="bg-stone-50 border-t border-stone-100 py-16 lg:py-20">
            <div class="max-w-6xl mx-auto px-6">
                <div class="mb-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Keep reading</p>
                    <h2 class="text-2xl font-black text-stone-900 tracking-tight">Related Posts</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedPosts as $relatedPost)
                        <article class="group bg-white rounded-2xl overflow-hidden border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all flex flex-col">
                            @if ($relatedPost->image)
                                <div class="aspect-video overflow-hidden bg-stone-100">
                                    <img src="{{ $relatedPost->image->base64_data }}" alt="{{ $relatedPost->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-stone-100 to-stone-200 flex items-center justify-center">
                                    <span class="text-4xl text-stone-300 font-black">{{ mb_substr($relatedPost->title, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center gap-2.5 mb-3">
                                    @if ($relatedPost->tags)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            {{ $relatedPost->tags[0] ?? '' }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-stone-400">{{ $relatedPost->published_at->format('M j, Y') }}</span>
                                </div>
                                <h3 class="font-bold text-stone-900 leading-snug mb-2 group-hover:text-amber-700 transition-colors flex-1">
                                    {{ $relatedPost->title }}
                                </h3>
                                <p class="text-stone-500 text-sm leading-relaxed mb-4">
                                    {{ $relatedPost->excerpt }}
                                </p>
                                <a href="{{ route('blog.post', $relatedPost) }}"
                                    class="mt-auto inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-amber-600 transition-colors">
                                    Read more
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Newsletter Signup Section -->
    <section class="bg-stone-900 py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-3">Enjoyed this post?</p>
                    <h2 class="text-3xl font-black text-white tracking-tight mb-4">
                        Get more like this<br>in your inbox
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

