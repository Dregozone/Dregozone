<div>
    <!-- Hero Section -->
    <section class="bg-stone-50 border-b border-stone-100 py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-5">
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Portfolio</span>
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
            </div>
            <h1 class="text-4xl font-black text-stone-900 tracking-tight mb-4">Projects</h1>
            <p class="text-lg text-stone-600 max-w-2xl mx-auto">
                A collection of things I've built — from side projects to open-source tools.
            </p>
        </div>
    </section>

    <!-- In Progress -->
    @if($inProgressProjects->isNotEmpty())
        <section class="bg-amber-50 border-b border-amber-100 py-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="mb-8">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-1">Currently building</p>
                    <h2 class="text-2xl font-black text-stone-900 tracking-tight">In Progress</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($inProgressProjects as $project)
                        <div class="group bg-white rounded-2xl p-6 border border-amber-100 hover:border-amber-300 hover:shadow-sm transition-all">
                            @if($project->uploadedImage)
                                <div class="mb-5 rounded-xl overflow-hidden bg-stone-100 aspect-video">
                                    <img src="{{ $project->uploadedImage->base64_data }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="font-bold text-stone-900 leading-snug">{{ $project->title }}</h3>
                                <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    In progress
                                </span>
                            </div>

                            <p class="text-stone-500 text-sm leading-relaxed mb-4">{{ $project->description }}</p>

                            @if($project->technologies)
                                <div class="flex flex-wrap gap-1.5 mb-5">
                                    @foreach($project->technologies as $tech)
                                        <span class="px-2 py-0.5 bg-stone-50 border border-stone-200 text-stone-500 text-xs font-medium rounded-md">{{ $tech }}</span>
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
            </div>
        </section>
    @endif

    <!-- Completed Projects -->
    <section class="bg-white py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="mb-10">
                <p class="text-xs font-bold uppercase tracking-widest text-stone-400 mb-1">Shipped</p>
                <h2 class="text-2xl font-black text-stone-900 tracking-tight">Completed Projects</h2>
            </div>

            @if($featuredProjects->isNotEmpty() || $completedProjects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProjects->merge($completedProjects)->unique('id') as $project)
                        <div class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all">
                            @if($project->uploadedImage)
                                <div class="mb-5 rounded-xl overflow-hidden bg-stone-200 aspect-video">
                                    <img src="{{ $project->uploadedImage->base64_data }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="font-bold text-stone-900 leading-snug">{{ $project->title }}</h3>
                                @if($project->featured)
                                    <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                                        Featured
                                    </span>
                                @else
                                    <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Complete
                                    </span>
                                @endif
                            </div>

                            <p class="text-stone-500 text-sm leading-relaxed mb-4">{{ $project->description }}</p>

                            @if($project->technologies)
                                <div class="flex flex-wrap gap-1.5 mb-5">
                                    @foreach($project->technologies as $tech)
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
</div>
