<div>

    <style>
        /* Pipeline circuit: scroll drives a packet through each stage; stages report back as they pass. */
        .aurora-wire { transform: scaleX(var(--p, 1)); transform-origin: left; }
        .aurora-packet { left: calc(var(--p, 1) * 100%); }
        .aurora-stage { transition: border-color .6s var(--ease-aurora), box-shadow .6s var(--ease-aurora); }
        .aurora-stage .aurora-status-done { display: none; }
        @for ($stage = 1; $stage <= 4; $stage++)
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) { border-color: rgb(63 230 192 / .35); box-shadow: 0 0 0 1px rgb(63 230 192 / .08), 0 20px 60px -30px rgb(63 230 192 / .5); }
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) .aurora-status-done { display: inline; }
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) .aurora-status-wait { display: none; }
        @endfor

        /* Terminal replays a real agent session line by line. */
        html.js-motion .aurora-terminal .aurora-term-line { opacity: 0; translate: -6px 0; transition: opacity .35s ease, translate .35s ease; transition-delay: calc(var(--i) * 260ms + 200ms); }
        html.js-motion .aurora-terminal.is-in .aurora-term-line { opacity: 1; translate: 0 0; }

    </style>

    {{-- =====================================================================
         HERO — generated plate, live aurora
    ====================================================================== --}}
    <section class="relative -mt-16 h-[100svh] min-h-[680px] max-h-[1000px] overflow-hidden bg-void" data-plate-frame aria-labelledby="aurora-title">
        <div class="absolute inset-0" data-parallax="0.3">
            <div class="absolute -inset-6" data-tilt="16">
                <img src="{{ asset('images/home/hero-aurora.webp') }}"
                    srcset="{{ asset('images/home/hero-aurora-960.webp') }} 960w, {{ asset('images/home/hero-aurora.webp') }} 1536w"
                    sizes="100vw" width="1536" height="864" alt="" fetchpriority="high" decoding="async" data-plate
                    class="aurora-plate h-full w-full object-cover">
                <canvas wire:ignore class="aurora-canvas absolute inset-0" data-aurora data-horizon="0.725" data-fps="#aurora-fps" aria-hidden="true"></canvas>
            </div>
        </div>
        <div class="aurora-grain pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(70%_60%_at_15%_45%,rgb(4_6_12/0.7),transparent_70%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-void to-transparent"></div>

        <div class="relative mx-auto flex h-full max-w-6xl flex-col justify-center px-6 pb-40 pt-16">
            <p class="font-mono aurora-rise mb-8 text-sm text-mute" style="--delay: .3s">
                <span class="text-glow-teal">anders@lab</span><span class="text-dim">:</span><span class="text-glow-cyan">~</span><span class="text-dim">$</span>
                <span data-typed="whoami" data-typed-delay="1100">whoami</span><span class="aurora-caret ml-1" aria-hidden="true"></span>
            </p>
            <h1 id="aurora-title" class="max-w-5xl text-[clamp(2.6rem,6.6vw,5.6rem)] font-medium leading-[0.95] tracking-[-0.045em]">
                <span class="aurora-rise block" style="--delay: 1.5s">Full-stack engineer.</span>
                <span class="aurora-rise block text-mute" style="--delay: 1.65s">Local-AI tinkerer.</span>
                <span class="aurora-rise aurora-gradient-text block pb-2" style="--delay: 1.8s">Interface obsessive.</span>
            </h1>
            <p class="aurora-rise mt-8 max-w-xl text-lg leading-relaxed text-mute" style="--delay: 2.1s">
                I'm Anders Learmonth. I build Laravel products end to end, run my own AI stack on a single GPU, and sweat the last five percent of an interface that nobody notices but everyone feels.
            </p>
            <div class="aurora-rise mt-10 flex flex-wrap items-center gap-6" style="--delay: 2.3s">
                <a href="#work" class="aurora-cta inline-flex items-center gap-3 rounded-full px-6 py-3.5 text-sm font-medium text-ink">
                    Explore the work
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-6-6m6 6l6-6" /></svg>
                </a>
                <a href="#lab" class="group inline-flex items-center gap-2 text-sm font-medium text-mute transition-colors hover:text-ink">
                    Tour the AI lab <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        {{-- Render HUD: what the visitor is looking at, honestly. --}}
        <a href="#plate" class="aurora-rise aurora-glass font-mono absolute bottom-8 right-6 hidden w-72 rounded-xl p-4 text-[11px] leading-6 text-mute transition-colors hover:border-edge-strong lg:block xl:right-[max(1.5rem,calc((100vw-72rem)/2))]" style="--delay: 2.6s">
            <span class="mb-2 flex items-center justify-between text-ink">
                <span>render.log</span>
                <span class="flex items-center gap-1.5 text-glow-teal"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-glow-teal"></span>live</span>
            </span>
            <span class="flex justify-between"><span class="text-dim">plate</span><span>SDXL Turbo · local</span></span>
            <span class="flex justify-between"><span class="text-dim">gpu</span><span>RTX 2060 Super · 8 GB</span></span>
            <span class="flex justify-between"><span class="text-dim">sky</span><span>&lt;canvas&gt; · <span id="aurora-fps">60</span> fps</span></span>
            <span class="flex justify-between"><span class="text-dim">weight</span><span>105 KB · vanilla JS</span></span>
        </a>
    </section>

    {{-- =====================================================================
         CAPABILITIES
    ====================================================================== --}}
    <section class="relative px-6 py-28 lg:py-36" aria-labelledby="aurora-capabilities-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div data-reveal>
                    <p class="font-mono mb-4 text-xs text-glow-teal">// what I bring</p>
                    <h2 id="aurora-capabilities-title" class="max-w-2xl text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Owning problems end to end, from the schema to the last pixel.</h2>
                </div>
                <p class="max-w-sm text-mute" data-reveal style="--i: 1">Readable code, calm collaboration and clear written thinking, applied wherever the work needs it.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($capabilities as $capability)
                    <article class="aurora-glass flex flex-col rounded-2xl p-6" data-spotlight data-reveal style="--i: {{ $loop->index }}">
                        <p class="font-mono text-xs text-dim">0x0{{ $loop->iteration }}</p>
                        <h3 class="mt-10 text-xl font-medium tracking-[-0.02em]">{{ $capability['title'] }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-mute">{{ $capability['description'] }}</p>
                        <ul class="font-mono mt-6 flex flex-wrap gap-1.5 text-[10px] text-mute">
                            @foreach ($capability['stack'] as $item)
                                <li class="rounded-md border border-edge px-2 py-1">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         THE LAB
    ====================================================================== --}}
    <section id="lab" class="relative overflow-hidden px-6 pb-12 pt-28 lg:pb-16 lg:pt-36" aria-labelledby="aurora-lab-title">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_40%_at_70%_0%,rgb(160_141_255/0.12),transparent_70%),radial-gradient(50%_40%_at_10%_30%,rgb(63_230_192/0.08),transparent_70%)]"></div>
        <div class="pointer-events-none absolute inset-0 opacity-40 aurora-grid [mask-image:radial-gradient(70%_60%_at_50%_30%,#000,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl">
            <div class="max-w-3xl" data-reveal>
                <p class="font-mono mb-4 text-xs text-glow-teal">// the lab</p>
                <h2 id="aurora-lab-title" class="text-[clamp(2.25rem,5.4vw,4.75rem)] font-medium leading-[0.98] tracking-[-0.045em]">A local AI lab, <span class="aurora-gradient-text">running on one desk.</span></h2>
                <p class="mt-8 max-w-2xl text-lg leading-relaxed text-mute">
                    Models on my own hardware, wired into Laravel apps, checked by other models, and orchestrated as a fleet of coding agents. Scroll to send a job through it.
                </p>
            </div>

            {{-- Pipeline circuit --}}
            <div class="relative mt-20" data-progress data-stages="4">
                <div class="absolute left-0 right-0 top-[2.15rem] hidden h-px bg-edge lg:block" aria-hidden="true">
                    <div class="aurora-wire absolute inset-0 aurora-gradient"></div>
                    <div class="aurora-packet absolute top-1/2 h-1.5 w-10 -translate-x-full -translate-y-1/2 rounded-full bg-gradient-to-r from-transparent to-white shadow-[0_0_18px_4px_rgb(92_211_255/0.6)]"></div>
                </div>
                <ol class="relative grid gap-4 lg:grid-cols-4">
                    @foreach ($aiPipeline as $stage)
                        <li class="aurora-stage aurora-glass rounded-2xl p-6">
                            <div class="font-mono flex items-center justify-between text-[11px]">
                                <span class="rounded-md border border-edge-strong bg-night px-2 py-1 text-ink">{{ $stage['key'] }}</span>
                                <span class="text-dim">
                                    <span class="aurora-status-wait">○ queued</span>
                                    <span class="aurora-status-done text-glow-teal">● passed</span>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium leading-snug tracking-[-0.015em]">{{ $stage['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-mute">{{ $stage['description'] }}</p>
                            <p class="font-mono mt-6 text-[10px] leading-5 text-dim">{{ implode(' / ', $stage['tools']) }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Agent session replay + Life OS --}}
            <div class="mt-28 grid gap-12 lg:grid-cols-[1fr_1.15fr] lg:items-center">
                <div>
                    <div data-reveal>
                        <p class="font-mono mb-4 text-xs text-glow-violet">// life-os</p>
                        <h3 class="text-[clamp(1.9rem,3.6vw,3rem)] font-medium leading-[1.02] tracking-[-0.035em]">A 3D world for running a team of agents.</h3>
                        <p class="mt-6 max-w-lg leading-relaxed text-mute">
                            A first-person office where each pod of desks owns a project. Every desk runs its own agent session in its own git worktree, so agents never trip over each other, and progress lands on live kanban boards inside the world.
                        </p>
                    </div>
                    <dl class="mt-10 grid grid-cols-3 gap-3">
                        @foreach ($lifeOsStats as $stat)
                            <div class="aurora-glass rounded-xl p-4" data-reveal style="--i: {{ $loop->index }}">
                                <dd class="text-4xl font-medium tracking-[-0.04em] text-ink" @if($stat['countable']) data-count="{{ $stat['value'] }}" @endif>{{ $stat['value'] }}</dd>
                                <dt class="mt-2 text-xs leading-snug text-mute">{{ $stat['label'] }}</dt>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="aurora-terminal aurora-glass overflow-hidden rounded-2xl" data-reveal>
                    <div class="flex items-center justify-between border-b border-edge px-4 py-3">
                        <div class="flex gap-1.5" aria-hidden="true">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f57]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#febc2e]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#28c840]/70"></span>
                        </div>
                        <p class="font-mono text-[11px] text-dim">pod 1 · desk 1 · ~/Herd/orbit</p>
                        <p class="font-mono text-[11px] text-glow-cyan">running</p>
                    </div>
                    @php
                        $sessionLines = [
                            ['prompt', '> add a "share list" button to the grocery list'],
                            ['say', "I'll start by reading how the pages are laid out."],
                            ['tool', 'Read(routes/web.php)', 'Read 64 lines'],
                            ['tool', 'Edit(app/Livewire/Page.php)', 'Updated with 18 additions'],
                            ['tool', 'Write(tests/Feature/PageTest.php)', 'Wrote 42 lines'],
                            ['tool', 'Bash(php artisan test)', 'Tests: 61 passed (184 assertions)'],
                            ['say', 'Done. The change is in, with a test, and the whole suite passes.'],
                        ];
                    @endphp
                    <div class="font-mono space-y-1.5 p-5 text-[12.5px] leading-6" role="img" aria-label="A replay of a Claude Code agent session adding a feature with a passing test suite.">
                        @foreach ($sessionLines as $line)
                            <div class="aurora-term-line" style="--i: {{ $loop->index }}">
                                @if ($line[0] === 'prompt')
                                    <p class="text-ink">{{ $line[1] }}</p>
                                @elseif ($line[0] === 'say')
                                    <p class="text-mute"><span class="text-glow-ember">●</span> {{ $line[1] }}</p>
                                @else
                                    <p class="text-ink"><span class="text-glow-ember">●</span> {{ $line[1] }}</p>
                                    <p class="pl-4 {{ str_starts_with($line[2], 'Tests') ? 'text-glow-teal' : 'text-dim' }}">└ {{ $line[2] }}</p>
                                @endif
                            </div>
                        @endforeach
                        <p class="aurora-term-line text-dim" style="--i: {{ count($sessionLines) }}">&gt; <span class="aurora-caret" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>

            <div class="mt-16" data-progress>
                <div class="aurora-wall grid gap-3 sm:grid-cols-2">
                    @foreach ($lifeOsImages as $image)
                        <figure class="group relative overflow-hidden rounded-xl border border-edge bg-night">
                            <img src="{{ asset('images/now/'.$image['file']) }}" alt="{{ $image['caption'] }}" width="1600" height="900" loading="lazy" decoding="async" class="aspect-video w-full object-cover opacity-80 transition-opacity duration-700 group-hover:opacity-100">
                            <figcaption class="font-mono absolute inset-x-0 bottom-0 bg-gradient-to-t from-[rgb(4_6_12/0.9)] to-transparent px-4 pb-3 pt-10 text-[11px] text-mute">{{ $image['caption'] }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>

            {{-- How the hero plate was made --}}
            <div id="plate" class="mt-28 scroll-mt-24" data-reveal>
                <div class="aurora-glass rounded-3xl p-6 sm:p-10">
                    <div class="grid gap-10 lg:grid-cols-[1fr_1.4fr] lg:items-center">
                        <div>
                            <p class="font-mono mb-4 text-xs text-glow-cyan">// hero.plate</p>
                            <h3 class="text-[clamp(1.6rem,2.8vw,2.4rem)] font-medium leading-[1.05] tracking-[-0.03em]">The sky at the top of this page was never photographed.</h3>
                            <p class="mt-5 leading-relaxed text-mute">
                                The plate was generated locally in ComfyUI in about forty seconds of GPU time. The aurora moving across it is drawn live in the browser, then mirrored into the lake.
                            </p>
                        </div>
                        <div class="font-mono flex flex-col gap-3 text-[11px] sm:flex-row sm:flex-wrap sm:items-center">
                            @foreach ($heroRecipe as $step)
                                <div class="flex items-center gap-3 sm:contents">
                                    <div class="rounded-lg border border-edge-strong bg-night px-3 py-2">
                                        <p class="text-ink">{{ $step['node'] }}</p>
                                        <p class="text-dim">{{ $step['detail'] }}</p>
                                    </div>
                                    <span class="aurora-flow hidden h-px w-6 sm:block" aria-hidden="true"></span>
                                </div>
                            @endforeach
                            <div class="overflow-hidden rounded-lg border border-[rgb(63_230_192/0.4)]">
                                <img src="{{ asset('images/home/hero-aurora-960.webp') }}" alt="The generated night-sky plate used in the hero" width="960" height="540" loading="lazy" class="h-14 w-24 object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         SELECTED WORK
    ====================================================================== --}}
    <section id="work" class="scroll-mt-16 px-6 py-28 lg:py-36" aria-labelledby="aurora-work-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 flex items-end justify-between gap-6" data-reveal>
                <div>
                    <p class="font-mono mb-4 text-xs text-glow-teal">// selected work</p>
                    <h2 id="aurora-work-title" class="text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Shipped, and still running.</h2>
                </div>
                <a href="{{ route('projects') }}" class="font-mono hidden text-xs text-mute transition-colors hover:text-ink sm:block">ls ./projects →</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredProjects as $project)
                    <x-aurora.project-card :project="$project" :index="$loop->index" />
                @empty
                    <p class="font-mono text-sm text-dim">// new work is being written up</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- =====================================================================
         FIELD NOTES
    ====================================================================== --}}
    <section class="px-6 pb-28 lg:pb-36" aria-labelledby="aurora-notes-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10 flex items-end justify-between gap-6" data-reveal>
                <div>
                    <p class="font-mono mb-4 text-xs text-glow-teal">// field notes</p>
                    <h2 id="aurora-notes-title" class="text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Writing it down.</h2>
                </div>
                <a href="{{ route('blog') }}" class="font-mono hidden text-xs text-mute transition-colors hover:text-ink sm:block">cat ./blog →</a>
            </div>
            <ul class="border-t border-edge">
                @forelse ($recentBlogPosts as $post)
                    <li class="border-b border-edge" data-reveal style="--i: {{ $loop->index }}">
                        <a href="{{ route('blog.post', $post) }}" class="group grid gap-2 py-6 sm:grid-cols-[9rem_1fr_auto_auto] sm:items-center sm:gap-8">
                            <time datetime="{{ $post->published_at->toDateString() }}" class="font-mono text-xs text-dim">{{ $post->published_at->format('Y-m-d') }}</time>
                            <span class="text-lg font-medium tracking-[-0.015em] transition-colors duration-300 group-hover:text-glow-cyan">{{ $post->title }}</span>
                            @if ($post->tags && count($post->tags) > 0)
                                <span class="font-mono hidden rounded-md border border-edge px-2 py-1 text-[10px] text-mute sm:block">{{ $post->tags[0] }}</span>
                            @else
                                <span class="hidden sm:block"></span>
                            @endif
                            <span class="hidden text-dim transition-transform duration-500 group-hover:translate-x-1 group-hover:text-glow-teal sm:block" aria-hidden="true">→</span>
                        </a>
                    </li>
                @empty
                    <li class="font-mono py-6 text-sm text-dim">// first entries compiling</li>
                @endforelse
            </ul>

            @if ($tools->isNotEmpty())
                <p class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 font-mono text-xs text-dim" data-reveal>
                    <span>// also shipped:</span>
                    @foreach ($tools as $tool)
                        <a href="{{ $tool->url }}" class="text-mute transition-colors hover:text-glow-cyan">{{ $tool->title }} ↗</a>
                    @endforeach
                </p>
            @endif
        </div>
    </section>

    {{-- =====================================================================
         CLOSING — the aurora returns
    ====================================================================== --}}
    <section class="relative overflow-hidden border-t border-edge px-6 py-32 lg:py-40" data-plate-frame>
        <canvas wire:ignore class="aurora-canvas absolute inset-0 [--aurora-opacity:0.6]" data-aurora data-envelope-start="0" data-envelope-end="0.5" aria-hidden="true"></canvas>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-void via-transparent to-void"></div>
        <div class="relative mx-auto grid max-w-6xl gap-14 lg:grid-cols-[1.3fr_1fr] lg:items-end">
            <div data-reveal>
                <h2 class="text-[clamp(2.5rem,6vw,5.25rem)] font-medium leading-[0.95] tracking-[-0.045em]">Got an interesting <span class="aurora-gradient-text">problem?</span></h2>
                <p class="mt-6 max-w-md text-lg leading-relaxed text-mute">The best projects start with a good question. I'm always happy to hear one.</p>
                <a href="{{ route('contact') }}" class="aurora-cta mt-10 inline-flex items-center gap-3 rounded-full px-6 py-3.5 text-sm font-medium">
                    Open a conversation <span aria-hidden="true">↗</span>
                </a>
            </div>
            <div class="aurora-glass rounded-2xl p-7" data-reveal style="--i: 1">
                <p class="font-mono mb-2 text-xs text-glow-teal">// subscribe</p>
                <p class="text-lg font-medium">Release notes from the lab.</p>
                <p class="mb-6 mt-1 text-sm text-mute">New writing, projects and experiments, only when they're worth opening.</p>
                @livewire('newsletter-signup')
            </div>
        </div>
    </section>
</div>
