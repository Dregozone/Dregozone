<div>
    <!-- Preview Banner -->
    <div class="sticky top-0 z-50 bg-amber-400 border-b-2 border-amber-500 shadow-md">
        <div class="max-w-4xl mx-auto px-6 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <div>
                    <span class="text-sm font-black text-amber-900 uppercase tracking-wide">Preview Mode</span>
                    <span class="hidden sm:inline text-sm text-amber-800 ml-2">— This blog post is not yet published</span>
                </div>
            </div>
            <a href="{{ route('admin.blog.edit', $post->id) }}"
                class="shrink-0 inline-flex items-center gap-1.5 bg-amber-900 hover:bg-amber-800 text-amber-100 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Back to Editor
            </a>
        </div>
    </div>

    <!-- Article Header -->
    <section class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="mb-8">
                <a href="{{ route('admin.blog.index') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold text-stone-500 hover:text-amber-600 transition-colors uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Blog Admin
                </a>
            </div>

            <header class="text-center">
                <div class="flex items-center justify-center gap-2.5 mb-5">
                    @if ($post->tags)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                            {{ $post->tags[0] ?? '' }}
                        </span>
                    @endif
                    <span class="text-xs text-stone-400">
                        {{ $post->published_at ? $post->published_at->format('M j, Y') : 'Unpublished' }}
                    </span>
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
            @if ($post->featured_image)
                <div class="mb-8">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                        class="w-full h-64 md:h-96 object-cover rounded-2xl">
                </div>
            @endif

            <article class="prose prose-lg prose-stone max-w-none tracking-wider text-lg font-['Inter',sans-serif]">
                {!! $post->content !!}
            </article>
        </div>
    </div>
</div>
