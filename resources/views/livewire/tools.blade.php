<div>
    <!-- Hero Section -->
    <section class="bg-stone-50 border-b border-stone-100 py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-5">
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Utilities</span>
                <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
            </div>
            <h1 class="text-4xl font-black text-stone-900 tracking-tight mb-4">Tools</h1>
            <p class="text-lg text-stone-600 max-w-2xl mx-auto">
                A collection of handy utilities I've built — free to use, no account required.
            </p>
        </div>
    </section>

    <!-- Tools List -->
    <section class="bg-white py-16 lg:py-20" id="main-content">
        <div class="max-w-6xl mx-auto px-6">
            @if($tools->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tools as $tool)
                        <a href="{{ $tool->url }}"
                            class="group bg-stone-50 rounded-2xl border border-stone-100 hover:border-stone-300 hover:shadow-sm transition-all overflow-hidden flex flex-col">
                            @if($tool->uploadedImage)
                                <div class="aspect-video overflow-hidden bg-stone-100">
                                    <img src="{{ $tool->uploadedImage->base64_data }}"
                                        alt="{{ $tool->title }}"
                                        loading="lazy"
                                        decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.965-3.964.337-.338a12.634 12.634 0 0 0 0-17.869 12.634 12.634 0 0 0-17.869 0 .75.75 0 0 0 0 1.061l.688.688" />
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6 flex flex-col flex-1">
                                <h2 class="font-bold text-stone-900 leading-snug mb-2 group-hover:text-amber-700 transition-colors">
                                    {{ $tool->title }}
                                </h2>
                                <p class="text-stone-500 text-sm leading-relaxed flex-1">{{ $tool->description }}</p>
                                <span class="mt-5 inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 group-hover:text-amber-600 transition-colors">
                                    Open tool
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 text-stone-400">
                    <p class="text-lg">Tools coming soon — check back!</p>
                </div>
            @endif
        </div>
    </section>
</div>
