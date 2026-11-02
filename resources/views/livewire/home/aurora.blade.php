<div class="design-aurora" data-landing-header>
    <script>document.documentElement.classList.add('js-motion')</script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600|jetbrains-mono:400,500&display=swap" rel="stylesheet">
    @vite('resources/js/landing.js')

    <style>
        body:has(.design-aurora) {
            --a-void: #04060c;
            --a-night: #070b15;
            --a-panel: rgb(13 19 34 / 0.55);
            --a-line: rgb(150 172 212 / 0.12);
            --a-line-strong: rgb(150 172 212 / 0.22);
            --a-text: #e7edf6;
            --a-mute: #8b98ac;
            --a-dim: #5d6a7e;
            --a-teal: #3fe6c0;
            --a-cyan: #5cd3ff;
            --a-violet: #a08dff;
            --a-pink: #e38dff;
            --a-ease: cubic-bezier(0.16, 1, 0.3, 1);
            --a-gradient: linear-gradient(100deg, var(--a-teal), var(--a-cyan) 45%, var(--a-violet) 80%, var(--a-pink));
            background: var(--a-void);
            color: var(--a-text);
        }

        .design-aurora { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; }
        .design-aurora .mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }
        .design-aurora ::selection { background: rgb(63 230 192 / .3); color: #fff; }
        .aurora-gradient-text { background: var(--a-gradient); -webkit-background-clip: text; background-clip: text; color: transparent; background-size: 200% 100%; animation: aurora-shift 12s ease-in-out infinite alternate; }
        @keyframes aurora-shift { to { background-position: 100% 0; } }

        /* Site chrome, re-lit for the night palette. */
        body:has(.design-aurora) > header { background: transparent; border-color: transparent; backdrop-filter: none; transition: background-color .6s var(--a-ease), border-color .6s var(--a-ease); }
        body:has(.design-aurora) > header.landing-header--scrolled { background: rgb(4 6 12 / .72); border-color: var(--a-line); backdrop-filter: blur(18px) saturate(1.4); }
        body:has(.design-aurora) > footer .md\:col-span-2 > a:first-child { font-family: "Space Grotesk", sans-serif; font-weight: 600; letter-spacing: -0.02em; }
        body:has(.design-aurora) > footer .md\:col-span-2 > a:first-child span { color: var(--a-teal); }
        .design-aurora form .bg-amber-400 { background: var(--a-teal); color: #03140f; }
        .design-aurora form .bg-amber-400:hover { background: #7ff0d6; }
        .design-aurora form input { background: rgb(4 6 12 / .6); border-color: var(--a-line-strong); }
        body:has(.design-aurora) > header .h-16 > a:first-child { color: var(--a-text); font-weight: 600; letter-spacing: -0.02em; }
        body:has(.design-aurora) > header .h-16 > a:first-child span { background: var(--a-gradient); -webkit-background-clip: text; background-clip: text; color: transparent; }
        body:has(.design-aurora) > header nav a, body:has(.design-aurora) > header .h-16 > div a, body:has(.design-aurora) > header button { color: var(--a-mute); }
        body:has(.design-aurora) > header nav a:hover { color: var(--a-text); }
        body:has(.design-aurora) > header a[href$="/register"] { background: rgb(255 255 255 / .06); color: var(--a-text); box-shadow: inset 0 0 0 1px var(--a-line-strong); }
        body:has(.design-aurora) > header #mobile-menu { background: rgb(4 6 12 / .95); border-color: var(--a-line); }
        body:has(.design-aurora) > footer { background: var(--a-void); border-color: var(--a-line); }
        body:has(.design-aurora) > footer .text-stone-900 { color: var(--a-text); }
        body:has(.design-aurora) > footer :is(.text-stone-500, .text-stone-600, .text-stone-400) { color: var(--a-mute); }
        body:has(.design-aurora) > footer .bg-stone-200 { background: rgb(255 255 255 / .06); color: var(--a-mute); }
        body:has(.design-aurora) > footer .border-stone-200 { border-color: var(--a-line); }
        body:has(.design-aurora) > footer .text-amber-600 { color: var(--a-teal); }

        /* Plate + live sky. */
        .aurora-plate { opacity: 0; filter: saturate(1.1) brightness(.85); transform: scale(1.08); transition: opacity 2s var(--a-ease), transform 3.2s var(--a-ease); }
        [data-plate-frame].is-loaded .aurora-plate { opacity: 1; transform: scale(1); }
        .aurora-canvas { width: 100%; height: 100%; mix-blend-mode: screen; filter: blur(3px) saturate(1.15); opacity: 0; transition: opacity 3s ease 1s; }
        [data-plate-frame].is-loaded .aurora-canvas { opacity: .95; }
        .aurora-grain { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.5'/%3E%3C/svg%3E"); opacity: .07; mix-blend-mode: overlay; }

        .aurora-rise { opacity: 0; translate: 0 22px; filter: blur(8px); transition: opacity 1.2s var(--a-ease), translate 1.2s var(--a-ease), filter 1.2s var(--a-ease); transition-delay: var(--delay, 0s); }
        .is-loaded .aurora-rise { opacity: 1; translate: 0 0; filter: blur(0); }
        .aurora-caret { display: inline-block; width: .55em; height: 1.1em; vertical-align: -0.2em; background: var(--a-teal); animation: aurora-blink 1.1s steps(1) infinite; }
        @keyframes aurora-blink { 50% { opacity: 0; } }

        /* Glass, lit by the cursor — the same light as the sky. */
        .aurora-glass:not(.absolute) { position: relative; }
        .aurora-glass { background: var(--a-panel); border: 1px solid var(--a-line); backdrop-filter: blur(14px) saturate(1.3); isolation: isolate; }
        .aurora-glass[data-spotlight]::before { content: ''; position: absolute; inset: -1px; border-radius: inherit; padding: 1px; z-index: -1; opacity: 0; transition: opacity .5s var(--a-ease);
            background: radial-gradient(320px circle at var(--mx, 50%) var(--my, 50%), rgb(92 211 255 / .55), rgb(160 141 255 / .25) 40%, transparent 70%);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; }
        .aurora-glass[data-spotlight]::after { content: ''; position: absolute; inset: 0; border-radius: inherit; z-index: -1; opacity: 0; transition: opacity .5s var(--a-ease);
            background: radial-gradient(420px circle at var(--mx, 50%) var(--my, 50%), rgb(63 230 192 / .07), transparent 60%); }
        .aurora-glass[data-spotlight]:hover::before, .aurora-glass[data-spotlight]:hover::after { opacity: 1; }

        /* Primary action: a slow ribbon of aurora travels around the border. */
        @property --a-angle { syntax: '<angle>'; inherits: false; initial-value: 0deg; }
        .aurora-cta { position: relative; isolation: isolate; }
        .aurora-cta::before { content: ''; position: absolute; inset: -1px; border-radius: inherit; z-index: -1; background: conic-gradient(from var(--a-angle), transparent 0 55%, var(--a-teal), var(--a-cyan), var(--a-violet), transparent 95%); animation: aurora-spin 5s linear infinite; }
        .aurora-cta::after { content: ''; position: absolute; inset: 1px; border-radius: inherit; z-index: -1; background: rgb(7 11 21 / .92); }
        @keyframes aurora-spin { to { --a-angle: 360deg; } }

        html.js-motion .design-aurora [data-reveal] { opacity: 0; translate: 0 32px; filter: blur(6px); transition: opacity 1s var(--a-ease), translate 1s var(--a-ease), filter 1s var(--a-ease); transition-delay: calc(var(--i, 0) * 90ms); }
        html.js-motion .design-aurora [data-reveal].is-in { opacity: 1; translate: 0 0; filter: blur(0); }

        /* Pipeline circuit: scroll drives a packet through each stage; stages report back as they pass. */
        .aurora-wire { transform: scaleX(var(--p, 1)); transform-origin: left; }
        .aurora-packet { left: calc(var(--p, 1) * 100%); }
        .aurora-stage { transition: border-color .6s var(--a-ease), box-shadow .6s var(--a-ease); }
        .aurora-stage .aurora-status-done { display: none; }
        @for ($stage = 1; $stage <= 4; $stage++)
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) { border-color: rgb(63 230 192 / .35); box-shadow: 0 0 0 1px rgb(63 230 192 / .08), 0 20px 60px -30px rgb(63 230 192 / .5); }
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) .aurora-status-done { display: inline; }
            [data-stage="{{ $stage }}"] .aurora-stage:nth-child(-n+{{ $stage }}) .aurora-status-wait { display: none; }
        @endfor

        /* Terminal replays a real agent session line by line. */
        html.js-motion .aurora-terminal .aurora-term-line { opacity: 0; translate: -6px 0; transition: opacity .35s ease, translate .35s ease; transition-delay: calc(var(--i) * 260ms + 200ms); }
        html.js-motion .aurora-terminal.is-in .aurora-term-line { opacity: 1; translate: 0 0; }

        /* Life OS: the screen wall stands up out of the floor as you arrive, like walking into the office. */
        .aurora-wall { transform: perspective(1600px) rotateX(calc((1 - var(--p, 1)) * 32deg)) scale(calc(0.88 + var(--p, 1) * 0.12)); transform-origin: 50% 100%; opacity: calc(0.35 + var(--p, 1) * 0.65); }

        /* ComfyUI graph: data flows along the wires between nodes. */
        .aurora-flow { background-image: linear-gradient(90deg, var(--a-teal) 50%, transparent 0); background-size: 10px 1px; animation: aurora-flow 1.2s linear infinite; }
        @keyframes aurora-flow { to { background-position: 10px 0; } }

        .aurora-row-arrow { transition: transform .5s var(--a-ease), color .5s var(--a-ease); }
        .aurora-row:hover .aurora-row-arrow { transform: translateX(4px); color: var(--a-teal); }
        .aurora-work-image img { transition: transform 1.4s var(--a-ease); }
        .aurora-work:hover .aurora-work-image img { transform: scale(1.04); }

        @media (prefers-reduced-motion: reduce) {
            .design-aurora *, .design-aurora *::before, .design-aurora *::after { animation: none !important; transition: none !important; }
            .aurora-plate, .aurora-rise { opacity: 1; transform: none; translate: none; filter: none; }
        }
    </style>

    {{-- =====================================================================
         HERO — generated plate, live aurora
    ====================================================================== --}}
    <section class="relative -mt-16 h-[100svh] min-h-[680px] max-h-[1000px] overflow-hidden bg-[var(--a-void)]" data-plate-frame aria-labelledby="aurora-title">
        <div class="absolute inset-0" data-parallax="0.3">
            <div class="absolute -inset-6" data-tilt="16">
                <img src="{{ asset('images/home/hero-aurora.webp') }}"
                    srcset="{{ asset('images/home/hero-aurora-960.webp') }} 960w, {{ asset('images/home/hero-aurora.webp') }} 1536w"
                    sizes="100vw" width="1536" height="864" alt="" fetchpriority="high" decoding="async" data-plate
                    class="aurora-plate h-full w-full object-cover">
                <canvas class="aurora-canvas absolute inset-0" data-aurora data-horizon="0.725" data-fps="#aurora-fps" aria-hidden="true"></canvas>
            </div>
        </div>
        <div class="aurora-grain pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(70%_60%_at_15%_45%,rgb(4_6_12/0.7),transparent_70%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-[var(--a-void)] to-transparent"></div>

        <div class="relative mx-auto flex h-full max-w-6xl flex-col justify-center px-6 pb-40 pt-16">
            <p class="mono aurora-rise mb-8 text-sm text-[var(--a-mute)]" style="--delay: .3s">
                <span class="text-[var(--a-teal)]">anders@lab</span><span class="text-[var(--a-dim)]">:</span><span class="text-[var(--a-cyan)]">~</span><span class="text-[var(--a-dim)]">$</span>
                <span data-typed="whoami" data-typed-delay="1100">whoami</span><span class="aurora-caret ml-1" aria-hidden="true"></span>
            </p>
            <h1 id="aurora-title" class="max-w-5xl text-[clamp(2.6rem,6.6vw,5.6rem)] font-medium leading-[0.95] tracking-[-0.045em]">
                <span class="aurora-rise block" style="--delay: 1.5s">Full-stack engineer.</span>
                <span class="aurora-rise block text-[var(--a-mute)]" style="--delay: 1.65s">Local-AI tinkerer.</span>
                <span class="aurora-rise aurora-gradient-text block pb-2" style="--delay: 1.8s">Interface obsessive.</span>
            </h1>
            <p class="aurora-rise mt-8 max-w-xl text-lg leading-relaxed text-[var(--a-mute)]" style="--delay: 2.1s">
                I'm Anders Learmonth. I build Laravel products end to end, run my own AI stack on a single GPU, and sweat the last five percent of an interface that nobody notices but everyone feels.
            </p>
            <div class="aurora-rise mt-10 flex flex-wrap items-center gap-6" style="--delay: 2.3s">
                <a href="#work" class="aurora-cta inline-flex items-center gap-3 rounded-full px-6 py-3.5 text-sm font-medium text-[var(--a-text)]">
                    Explore the work
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-6-6m6 6l6-6" /></svg>
                </a>
                <a href="#lab" class="group inline-flex items-center gap-2 text-sm font-medium text-[var(--a-mute)] transition-colors hover:text-[var(--a-text)]">
                    Tour the AI lab <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        {{-- Render HUD: what the visitor is looking at, honestly. --}}
        <a href="#plate" class="aurora-rise aurora-glass mono absolute bottom-8 right-6 hidden w-72 rounded-xl p-4 text-[11px] leading-6 text-[var(--a-mute)] transition-colors hover:border-[var(--a-line-strong)] lg:block xl:right-[max(1.5rem,calc((100vw-72rem)/2))]" style="--delay: 2.6s">
            <span class="mb-2 flex items-center justify-between text-[var(--a-text)]">
                <span>render.log</span>
                <span class="flex items-center gap-1.5 text-[var(--a-teal)]"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[var(--a-teal)]"></span>live</span>
            </span>
            <span class="flex justify-between"><span class="text-[var(--a-dim)]">plate</span><span>SDXL Turbo · local</span></span>
            <span class="flex justify-between"><span class="text-[var(--a-dim)]">gpu</span><span>RTX 2060 Super · 8 GB</span></span>
            <span class="flex justify-between"><span class="text-[var(--a-dim)]">sky</span><span>&lt;canvas&gt; · <span id="aurora-fps">60</span> fps</span></span>
            <span class="flex justify-between"><span class="text-[var(--a-dim)]">weight</span><span>105 KB · vanilla JS</span></span>
        </a>
    </section>

    {{-- =====================================================================
         CAPABILITIES
    ====================================================================== --}}
    <section class="relative px-6 py-28 lg:py-36" aria-labelledby="aurora-capabilities-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div data-reveal>
                    <p class="mono mb-4 text-xs text-[var(--a-teal)]">// what I bring</p>
                    <h2 id="aurora-capabilities-title" class="max-w-2xl text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Owning problems end to end, from the schema to the last pixel.</h2>
                </div>
                <p class="max-w-sm text-[var(--a-mute)]" data-reveal style="--i: 1">Readable code, calm collaboration and clear written thinking, applied wherever the work needs it.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($capabilities as $capability)
                    <article class="aurora-glass flex flex-col rounded-2xl p-6" data-spotlight data-reveal style="--i: {{ $loop->index }}">
                        <p class="mono text-xs text-[var(--a-dim)]">0x0{{ $loop->iteration }}</p>
                        <h3 class="mt-10 text-xl font-medium tracking-[-0.02em]">{{ $capability['title'] }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-[var(--a-mute)]">{{ $capability['description'] }}</p>
                        <ul class="mono mt-6 flex flex-wrap gap-1.5 text-[10px] text-[var(--a-mute)]">
                            @foreach ($capability['stack'] as $item)
                                <li class="rounded-md border border-[var(--a-line)] px-2 py-1">{{ $item }}</li>
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
        <div class="pointer-events-none absolute inset-0 opacity-40 [background-image:linear-gradient(var(--a-line)_1px,transparent_1px),linear-gradient(90deg,var(--a-line)_1px,transparent_1px)] [background-size:64px_64px] [mask-image:radial-gradient(70%_60%_at_50%_30%,#000,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl">
            <div class="max-w-3xl" data-reveal>
                <p class="mono mb-4 text-xs text-[var(--a-teal)]">// the lab</p>
                <h2 id="aurora-lab-title" class="text-[clamp(2.25rem,5.4vw,4.75rem)] font-medium leading-[0.98] tracking-[-0.045em]">A local AI lab, <span class="aurora-gradient-text">running on one desk.</span></h2>
                <p class="mt-8 max-w-2xl text-lg leading-relaxed text-[var(--a-mute)]">
                    Models on my own hardware, wired into Laravel apps, checked by other models, and orchestrated as a fleet of coding agents. Scroll to send a job through it.
                </p>
            </div>

            {{-- Pipeline circuit --}}
            <div class="relative mt-20" data-progress data-stages="4">
                <div class="absolute left-0 right-0 top-[2.15rem] hidden h-px bg-[var(--a-line)] lg:block" aria-hidden="true">
                    <div class="aurora-wire absolute inset-0 bg-[image:var(--a-gradient)]"></div>
                    <div class="aurora-packet absolute top-1/2 h-1.5 w-10 -translate-x-full -translate-y-1/2 rounded-full bg-gradient-to-r from-transparent to-white shadow-[0_0_18px_4px_rgb(92_211_255/0.6)]"></div>
                </div>
                <ol class="relative grid gap-4 lg:grid-cols-4">
                    @foreach ($aiPipeline as $stage)
                        <li class="aurora-stage aurora-glass rounded-2xl p-6">
                            <div class="mono flex items-center justify-between text-[11px]">
                                <span class="rounded-md border border-[var(--a-line-strong)] bg-[var(--a-night)] px-2 py-1 text-[var(--a-text)]">{{ $stage['key'] }}</span>
                                <span class="text-[var(--a-dim)]">
                                    <span class="aurora-status-wait">○ queued</span>
                                    <span class="aurora-status-done text-[var(--a-teal)]">● passed</span>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium leading-snug tracking-[-0.015em]">{{ $stage['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[var(--a-mute)]">{{ $stage['description'] }}</p>
                            <p class="mono mt-6 text-[10px] leading-5 text-[var(--a-dim)]">{{ implode(' / ', $stage['tools']) }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Agent session replay + Life OS --}}
            <div class="mt-28 grid gap-12 lg:grid-cols-[1fr_1.15fr] lg:items-center">
                <div>
                    <div data-reveal>
                        <p class="mono mb-4 text-xs text-[var(--a-violet)]">// life-os</p>
                        <h3 class="text-[clamp(1.9rem,3.6vw,3rem)] font-medium leading-[1.02] tracking-[-0.035em]">A 3D world for running a team of agents.</h3>
                        <p class="mt-6 max-w-lg leading-relaxed text-[var(--a-mute)]">
                            A first-person office where each pod of desks owns a project. Every desk runs its own agent session in its own git worktree, so agents never trip over each other, and progress lands on live kanban boards inside the world.
                        </p>
                    </div>
                    <dl class="mt-10 grid grid-cols-3 gap-3">
                        @foreach ($lifeOsStats as $stat)
                            <div class="aurora-glass rounded-xl p-4" data-reveal style="--i: {{ $loop->index }}">
                                <dd class="text-4xl font-medium tracking-[-0.04em] text-[var(--a-text)]" @if($stat['countable']) data-count="{{ $stat['value'] }}" @endif>{{ $stat['value'] }}</dd>
                                <dt class="mt-2 text-xs leading-snug text-[var(--a-mute)]">{{ $stat['label'] }}</dt>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="aurora-terminal aurora-glass overflow-hidden rounded-2xl" data-reveal>
                    <div class="flex items-center justify-between border-b border-[var(--a-line)] px-4 py-3">
                        <div class="flex gap-1.5" aria-hidden="true">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f57]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#febc2e]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#28c840]/70"></span>
                        </div>
                        <p class="mono text-[11px] text-[var(--a-dim)]">pod 1 · desk 1 · ~/Herd/orbit</p>
                        <p class="mono text-[11px] text-[var(--a-cyan)]">running</p>
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
                    <div class="mono space-y-1.5 p-5 text-[12.5px] leading-6" role="img" aria-label="A replay of a Claude Code agent session adding a feature with a passing test suite.">
                        @foreach ($sessionLines as $line)
                            <div class="aurora-term-line" style="--i: {{ $loop->index }}">
                                @if ($line[0] === 'prompt')
                                    <p class="text-[var(--a-text)]">{{ $line[1] }}</p>
                                @elseif ($line[0] === 'say')
                                    <p class="text-[var(--a-mute)]"><span class="text-[#f0a37a]">●</span> {{ $line[1] }}</p>
                                @else
                                    <p class="text-[var(--a-text)]"><span class="text-[#f0a37a]">●</span> {{ $line[1] }}</p>
                                    <p class="pl-4 {{ str_starts_with($line[2], 'Tests') ? 'text-[var(--a-teal)]' : 'text-[var(--a-dim)]' }}">└ {{ $line[2] }}</p>
                                @endif
                            </div>
                        @endforeach
                        <p class="aurora-term-line text-[var(--a-dim)]" style="--i: {{ count($sessionLines) }}">&gt; <span class="aurora-caret" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>

            <div class="mt-16" data-progress>
                <div class="aurora-wall grid gap-3 sm:grid-cols-2">
                    @foreach ($lifeOsImages as $image)
                        <figure class="group relative overflow-hidden rounded-xl border border-[var(--a-line)] bg-[var(--a-night)]">
                            <img src="{{ asset('images/now/'.$image['file']) }}" alt="{{ $image['caption'] }}" width="1600" height="900" loading="lazy" decoding="async" class="aspect-video w-full object-cover opacity-80 transition-opacity duration-700 group-hover:opacity-100">
                            <figcaption class="mono absolute inset-x-0 bottom-0 bg-gradient-to-t from-[rgb(4_6_12/0.9)] to-transparent px-4 pb-3 pt-10 text-[11px] text-[var(--a-mute)]">{{ $image['caption'] }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>

            {{-- How the hero plate was made --}}
            <div id="plate" class="mt-28 scroll-mt-24" data-reveal>
                <div class="aurora-glass rounded-3xl p-6 sm:p-10">
                    <div class="grid gap-10 lg:grid-cols-[1fr_1.4fr] lg:items-center">
                        <div>
                            <p class="mono mb-4 text-xs text-[var(--a-cyan)]">// hero.plate</p>
                            <h3 class="text-[clamp(1.6rem,2.8vw,2.4rem)] font-medium leading-[1.05] tracking-[-0.03em]">The sky at the top of this page was never photographed.</h3>
                            <p class="mt-5 leading-relaxed text-[var(--a-mute)]">
                                The plate was generated locally in ComfyUI in about forty seconds of GPU time. The aurora moving across it is drawn live in the browser, then mirrored into the lake.
                            </p>
                        </div>
                        <div class="mono flex flex-col gap-3 text-[11px] sm:flex-row sm:flex-wrap sm:items-center">
                            @foreach ($heroRecipe as $step)
                                <div class="flex items-center gap-3 sm:contents">
                                    <div class="rounded-lg border border-[var(--a-line-strong)] bg-[var(--a-night)] px-3 py-2">
                                        <p class="text-[var(--a-text)]">{{ $step['node'] }}</p>
                                        <p class="text-[var(--a-dim)]">{{ $step['detail'] }}</p>
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
                    <p class="mono mb-4 text-xs text-[var(--a-teal)]">// selected work</p>
                    <h2 id="aurora-work-title" class="text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Shipped, and still running.</h2>
                </div>
                <a href="{{ route('projects') }}" class="mono hidden text-xs text-[var(--a-mute)] transition-colors hover:text-[var(--a-text)] sm:block">ls ./projects →</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredProjects as $project)
                    @php $projectImage = $project->uploadedImage?->base64_data ?? $project->image; @endphp
                    <article class="aurora-work aurora-glass flex flex-col overflow-hidden rounded-2xl" data-spotlight data-reveal style="--i: {{ $loop->index }}">
                        <div class="aurora-work-image aspect-video overflow-hidden border-b border-[var(--a-line)] bg-[var(--a-night)]">
                            @if ($projectImage)
                                <img src="{{ $projectImage }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center bg-[radial-gradient(80%_80%_at_70%_20%,rgb(160_141_255/0.18),transparent),radial-gradient(60%_60%_at_20%_80%,rgb(63_230_192/0.12),transparent)]">
                                    <span class="mono text-xs text-[var(--a-dim)]">{{ Str::slug($project->title) }}/</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="mono mb-4 flex items-center gap-2 text-[11px]">
                                <span @class(['h-1.5 w-1.5 rounded-full', 'bg-[var(--a-teal)]' => $project->status === 'completed', 'bg-[var(--a-violet)] animate-pulse' => $project->status !== 'completed'])></span>
                                <span class="text-[var(--a-mute)]">{{ str_replace('_', ' ', $project->status) }}</span>
                            </div>
                            <h3 class="text-xl font-medium tracking-[-0.02em]">{{ $project->title }}</h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-[var(--a-mute)]">{{ $project->description }}</p>
                            @if ($project->technologies)
                                <p class="mono mt-6 text-[10px] text-[var(--a-dim)]">{{ implode(' / ', array_slice($project->technologies, 0, 4)) }}</p>
                            @endif
                            <div class="mono mt-5 flex gap-5 text-xs">
                                @if ($project->url)
                                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-[var(--a-cyan)] hover:text-[var(--a-text)]">open ↗</a>
                                @endif
                                @if ($project->github_url)
                                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="text-[var(--a-cyan)] hover:text-[var(--a-text)]">source ↗</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="mono text-sm text-[var(--a-dim)]">// new work is being written up</p>
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
                    <p class="mono mb-4 text-xs text-[var(--a-teal)]">// field notes</p>
                    <h2 id="aurora-notes-title" class="text-[clamp(2rem,4.4vw,3.6rem)] font-medium leading-[1.02] tracking-[-0.035em]">Writing it down.</h2>
                </div>
                <a href="{{ route('blog') }}" class="mono hidden text-xs text-[var(--a-mute)] transition-colors hover:text-[var(--a-text)] sm:block">cat ./blog →</a>
            </div>
            <ul class="border-t border-[var(--a-line)]">
                @forelse ($recentBlogPosts as $post)
                    <li class="border-b border-[var(--a-line)]" data-reveal style="--i: {{ $loop->index }}">
                        <a href="{{ route('blog.post', $post) }}" class="aurora-row grid gap-2 py-6 sm:grid-cols-[9rem_1fr_auto_auto] sm:items-center sm:gap-8">
                            <time datetime="{{ $post->published_at->toDateString() }}" class="mono text-xs text-[var(--a-dim)]">{{ $post->published_at->format('Y-m-d') }}</time>
                            <span class="text-lg font-medium tracking-[-0.015em]">{{ $post->title }}</span>
                            @if ($post->tags && count($post->tags) > 0)
                                <span class="mono hidden rounded-md border border-[var(--a-line)] px-2 py-1 text-[10px] text-[var(--a-mute)] sm:block">{{ $post->tags[0] }}</span>
                            @else
                                <span class="hidden sm:block"></span>
                            @endif
                            <span class="aurora-row-arrow hidden text-[var(--a-dim)] sm:block" aria-hidden="true">→</span>
                        </a>
                    </li>
                @empty
                    <li class="mono py-6 text-sm text-[var(--a-dim)]">// first entries compiling</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- =====================================================================
         CLOSING — the aurora returns
    ====================================================================== --}}
    <section class="relative overflow-hidden border-t border-[var(--a-line)] px-6 py-32 lg:py-40" data-plate-frame>
        <canvas class="aurora-canvas absolute inset-0 !opacity-60" data-aurora data-envelope-start="0" data-envelope-end="0.5" aria-hidden="true"></canvas>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-[var(--a-void)] via-transparent to-[var(--a-void)]"></div>
        <div class="relative mx-auto grid max-w-6xl gap-14 lg:grid-cols-[1.3fr_1fr] lg:items-end">
            <div data-reveal>
                <h2 class="text-[clamp(2.5rem,6vw,5.25rem)] font-medium leading-[0.95] tracking-[-0.045em]">Got an interesting <span class="aurora-gradient-text">problem?</span></h2>
                <p class="mt-6 max-w-md text-lg leading-relaxed text-[var(--a-mute)]">The best projects start with a good question. I'm always happy to hear one.</p>
                <a href="{{ route('contact') }}" class="aurora-cta mt-10 inline-flex items-center gap-3 rounded-full px-6 py-3.5 text-sm font-medium">
                    Open a conversation <span aria-hidden="true">↗</span>
                </a>
            </div>
            <div class="aurora-glass rounded-2xl p-7" data-reveal style="--i: 1">
                <p class="mono mb-2 text-xs text-[var(--a-teal)]">// subscribe</p>
                <p class="text-lg font-medium">Release notes from the lab.</p>
                <p class="mb-6 mt-1 text-sm text-[var(--a-mute)]">New writing, projects and experiments, only when they're worth opening.</p>
                @livewire('newsletter-signup')
            </div>
        </div>
    </section>
</div>
