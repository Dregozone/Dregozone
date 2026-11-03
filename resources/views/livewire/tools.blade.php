<div>
    <x-aurora.page-header path="~/tools" eyebrow="ls ./bin"
        description="A collection of handy utilities I've built — free to use, no account required.">
        <x-slot:title>Small tools, <span class="aurora-gradient-text">sharp edges.</span></x-slot:title>
    </x-aurora.page-header>

    <section class="px-6 py-16 lg:py-24">
        <div class="mx-auto max-w-6xl">
            @if ($tools->isNotEmpty())
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($tools as $tool)
                        <a href="{{ $tool->url }}" class="group aurora-glass flex flex-col overflow-hidden rounded-2xl" data-spotlight data-reveal style="--i: {{ $loop->index % 3 }}">
                            <div class="aspect-video overflow-hidden border-b border-edge bg-night">
                                @if ($tool->uploadedImage)
                                    <img src="{{ $tool->uploadedImage->base64_data }}" alt="{{ $tool->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-[1.4s] ease-aurora group-hover:scale-[1.04]">
                                @else
                                    <div class="flex h-full flex-col justify-between bg-[radial-gradient(80%_80%_at_80%_10%,rgb(92_211_255/0.16),transparent),radial-gradient(60%_60%_at_10%_90%,rgb(63_230_192/0.12),transparent)] p-5 font-mono text-xs">
                                        <span class="text-dim">$ ./{{ Str::slug($tool->title) }}</span>
                                        <span class="text-glow-teal">ready<span class="aurora-caret ml-1" aria-hidden="true"></span></span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <h2 class="text-xl font-medium tracking-[-0.02em] transition-colors duration-300 group-hover:text-glow-cyan">{{ $tool->title }}</h2>
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-mute">{{ $tool->description }}</p>
                                <span class="mt-6 inline-flex items-center gap-2 font-mono text-xs text-dim transition-colors group-hover:text-glow-teal">
                                    open tool <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="aurora-glass rounded-2xl px-6 py-16 text-center">
                    <p class="font-mono text-sm text-dim">// compiling</p>
                    <p class="mt-3 text-lg text-mute">Tools coming soon — check back!</p>
                </div>
            @endif
        </div>
    </section>
</div>
