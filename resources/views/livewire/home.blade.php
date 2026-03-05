<div>
    {{-- =====================================================================
         HERO
    ====================================================================== --}}
    <section class="bg-stone-50 border-b border-stone-100 overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 py-20 lg:py-28">
            <div class="grid lg:grid-cols-[1fr_400px] gap-14 items-center">

                {{-- Left: intro text --}}
                <div>
                    <div class="flex items-center gap-3 mb-7">
                        <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Welcome to my corner of the web</span>
                    </div>

                    <h1 class="text-[clamp(2.75rem,7vw,5rem)] font-black text-stone-900 leading-[0.9] tracking-tight mb-7">
                        Hey, I'm<br>
                        <span class="text-amber-500">Anders</span><br>
                        Learmonth.
                    </h1>

                    <p class="text-lg text-stone-600 leading-relaxed mb-8 max-w-lg">
                        I build things for the web, write honestly about what I'm learning, and find real joy in exploring languages — both human ones and programming ones.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-10">
                        @foreach(['Laravel', 'Livewire', 'PHP', 'Language Learning', 'Open Source', 'Side Projects'] as $interest)
                            <span class="inline-flex items-center px-3 py-1.5 bg-white border border-stone-200 text-stone-600 text-xs font-semibold rounded-full">
                                {{ $interest }}
                            </span>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('blog') }}"
                            class="inline-flex items-center gap-2 bg-stone-900 text-white px-6 py-3.5 rounded-full text-sm font-bold hover:bg-stone-700 transition-colors">
                            Read the blog
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center gap-2 bg-white border border-stone-300 text-stone-700 px-6 py-3.5 rounded-full text-sm font-bold hover:border-stone-500 hover:text-stone-900 transition-colors">
                            Say hello
                        </a>
                    </div>
                </div>

                {{-- Right: activity peek cards --}}
                <div class="hidden lg:flex flex-col gap-4">
                    {{-- Latest post card --}}
                    @if($recentBlogPosts->isNotEmpty())
                        @php $latestPost = $recentBlogPosts->first(); @endphp
                        <a href="{{ route('blog.post', $latestPost) }}"
                            class="group block bg-white rounded-2xl p-6 border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Latest post</span>
                                <span class="text-xs text-stone-400">{{ $latestPost->published_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="font-bold text-stone-900 leading-snug group-hover:text-amber-700 transition-colors">
                                {{ $latestPost->title }}
                            </h3>
                            @if($latestPost->excerpt)
                                <p class="mt-2 text-sm text-stone-500 line-clamp-2">{{ $latestPost->excerpt }}</p>
                            @endif
                            <span class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-stone-500 group-hover:text-stone-700 transition-colors">
                                Read it
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </span>
                        </a>
                    @endif

                    {{-- Currently building card --}}
                    @if($inProgressProjects->isNotEmpty())
                        <div class="bg-amber-400 rounded-2xl p-6">
                            <span class="text-xs font-bold uppercase tracking-widest text-amber-900">Currently building</span>
                            <div class="mt-3 space-y-2">
                                @foreach($inProgressProjects->take(2) as $project)
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-700 shrink-0"></span>
                                        <span class="text-sm font-semibold text-amber-900">{{ $project->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-stone-900 rounded-2xl p-6">
                            <span class="text-xs font-bold uppercase tracking-widest text-stone-400">What I enjoy</span>
                            <p class="mt-3 text-stone-300 text-sm leading-relaxed">Crafting clean code, writing about it, and picking up new languages along the way.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- =====================================================================
         BUILDING NOW STRIP (mobile / overflow fallback when no right column)
    ====================================================================== --}}
    @if($inProgressProjects->isNotEmpty())
        <div class="bg-amber-400 border-b border-amber-300">
            <div class="max-w-6xl mx-auto px-6 py-3 flex items-center gap-5 overflow-x-auto scrollbar-none">
                <span class="text-amber-900 text-xs font-black uppercase tracking-widest whitespace-nowrap">Building now ↗</span>
                <div class="flex items-center gap-5 shrink-0">
                    @foreach($inProgressProjects as $project)
                        <span class="text-amber-900 text-sm font-semibold whitespace-nowrap">{{ $project->title }}</span>
                        @unless($loop->last)
                            <span class="text-amber-600 font-light">·</span>
                        @endunless
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- =====================================================================
         WORK / PROJECTS
    ====================================================================== --}}
    <section class="bg-white py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-12">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Portfolio</p>
                    <h2 class="text-3xl font-black text-stone-900 tracking-tight">Things I've built</h2>
                </div>
            </div>

            @if($featuredProjects->isNotEmpty() || $completedProjects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProjects->merge($completedProjects)->unique('id')->take(6) as $project)
                        <div class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all">
                            @if($project->image)
                                <div class="mb-5 rounded-xl overflow-hidden bg-stone-200 aspect-video">
                                    <img src="{{ $project->image }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="font-bold text-stone-900 leading-snug">{{ $project->title }}</h3>
                                <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full
                                    @if($project->status === 'completed') bg-emerald-100 text-emerald-700
                                    @elseif($project->status === 'in_progress') bg-amber-100 text-amber-700
                                    @else bg-stone-200 text-stone-600 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($project->status === 'completed') bg-emerald-500
                                        @elseif($project->status === 'in_progress') bg-amber-500
                                        @else bg-stone-400 @endif"></span>
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>

                            <p class="text-stone-500 text-sm leading-relaxed mb-4">{{ $project->description }}</p>

                            @if($project->technologies)
                                <div class="flex flex-wrap gap-1.5 mb-5">
                                    @foreach(array_slice($project->technologies, 0, 4) as $tech)
                                        <span class="px-2 py-0.5 bg-white border border-stone-200 text-stone-500 text-xs font-medium rounded-md">{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center gap-4">
                                @if($project->url)
                                    <a href="{{ $project->url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-stone-700 hover:text-amber-600 transition-colors">
                                        Live site
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                @endif
                                @if($project->github_url)
                                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-stone-700 hover:text-amber-600 transition-colors">
                                        Source
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 text-stone-400">
                    <p class="text-lg">Projects coming soon — check back!</p>
                </div>
            @endif

        </div>
    </section>

    {{-- =====================================================================
         WRITING / BLOG
    ====================================================================== --}}
    <section class="bg-stone-50 py-20 lg:py-24 border-t border-stone-100">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-12">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">From the blog</p>
                    <h2 class="text-3xl font-black text-stone-900 tracking-tight">Writing</h2>
                </div>
                <a href="{{ route('blog') }}"
                    class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-stone-600 hover:text-stone-900 transition-colors">
                    All posts
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recentBlogPosts as $post)
                    <article class="group bg-white rounded-2xl overflow-hidden border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all flex flex-col">
                        @if($post->featured_image)
                            <div class="aspect-video overflow-hidden bg-stone-100">
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-stone-100 to-stone-200 flex items-center justify-center">
                                <span class="text-4xl text-stone-300 font-black">{{ mb_substr($post->title, 0, 1) }}</span>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-2.5 mb-3">
                                @if($post->tags && count($post->tags) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                        {{ $post->tags[0] }}
                                    </span>
                                @endif
                                <span class="text-xs text-stone-400">{{ $post->published_at->format('M j, Y') }}</span>
                            </div>

                            <h3 class="font-bold text-stone-900 leading-snug mb-2 group-hover:text-amber-700 transition-colors">
                                <a href="{{ route('blog.post', $post) }}">{{ $post->title }}</a>
                            </h3>

                            @if($post->excerpt)
                                <p class="text-stone-500 text-sm leading-relaxed mb-4 flex-1">
                                    {{ Str::limit($post->excerpt, 100) }}
                                </p>
                            @endif

                            <a href="{{ route('blog.post', $post) }}"
                                class="mt-auto inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-amber-600 transition-colors">
                                Read more
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-stone-400 text-lg">No posts yet — coming soon!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10 sm:hidden text-center">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center gap-2 bg-stone-900 text-white px-6 py-3 rounded-full text-sm font-bold hover:bg-stone-700 transition-colors">
                    All posts
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

        </div>
    </section>

    {{-- =====================================================================
         TOPICS / INTERESTS CLOUD
    ====================================================================== --}}
    <section class="bg-white py-20 lg:py-24 border-t border-stone-100">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-3">What I write about</p>
                <h2 class="text-3xl font-black text-stone-900 tracking-tight mb-4">Topics & interests</h2>
                <p class="text-stone-500 text-base leading-relaxed mb-10">
                    Not just code. I write about learning itself — picking up languages, building habits, exploring ideas, and the occasional deep-dive into something technical.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                @php
                    $topics = [
                        ['label' => 'Laravel & PHP', 'colour' => 'bg-red-50 text-red-700 border-red-200'],
                        ['label' => 'Livewire', 'colour' => 'bg-pink-50 text-pink-700 border-pink-200'],
                        ['label' => 'Language Learning', 'colour' => 'bg-violet-50 text-violet-700 border-violet-200'],
                        ['label' => 'Open Source', 'colour' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        ['label' => 'Side Projects', 'colour' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        ['label' => 'JavaScript', 'colour' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
                        ['label' => 'Career & Learning', 'colour' => 'bg-sky-50 text-sky-700 border-sky-200'],
                        ['label' => 'Productivity', 'colour' => 'bg-teal-50 text-teal-700 border-teal-200'],
                        ['label' => 'Building in Public', 'colour' => 'bg-orange-50 text-orange-700 border-orange-200'],
                        ['label' => 'Personal', 'colour' => 'bg-rose-50 text-rose-700 border-rose-200'],
                    ];
                @endphp
                @foreach($topics as $topic)
                    <a href="{{ route('blog', ['search' => $topic['label']]) }}"
                        class="inline-flex items-center px-4 py-2 rounded-full border text-sm font-semibold transition-all hover:scale-105 hover:shadow-sm {{ $topic['colour'] }}">
                        {{ $topic['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         NEWSLETTER
    ====================================================================== --}}
    <section class="bg-stone-900 py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-3">Stay in the loop</p>
                    <h2 class="text-3xl font-black text-white tracking-tight mb-4">
                        Get new posts<br>in your inbox
                    </h2>
                    <p class="text-stone-400 leading-relaxed">
                        No spam, ever. Just occasional updates when I publish something worth reading — a new project, a lesson learned, or a topic I can't stop thinking about.
                    </p>
                </div>
                <div>
                    @livewire('newsletter-signup')
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         CONNECT CTA
    ====================================================================== --}}
    <section class="bg-amber-400 py-16">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-black text-amber-900 tracking-tight">Have something in mind?</h2>
                <p class="text-amber-800 mt-1">I'd love to hear from you — work, ideas, or just a hello.</p>
            </div>
            <a href="{{ route('contact') }}"
                class="shrink-0 bg-amber-900 text-amber-100 px-7 py-3.5 rounded-full font-bold text-sm hover:bg-stone-900 transition-colors">
                Get in touch
            </a>
        </div>
    </section>
</div>
