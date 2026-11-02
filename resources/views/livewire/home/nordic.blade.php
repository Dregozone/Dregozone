<div class="design-nordic" data-landing-header>
    <script>document.documentElement.classList.add('js-motion')</script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:300,300i,400,400i|inter:400,500,600&display=swap" rel="stylesheet">
    @vite('resources/js/landing.js')

    <style>
        body:has(.design-nordic) {
            --n-paper: #f2f3f0;
            --n-mist: #e7eae8;
            --n-ink: #17212a;
            --n-slate: #475663;
            --n-mute: #84929c;
            --n-line: rgb(23 33 42 / 0.12);
            --n-ember: #b3732c;
            --n-deep: #131d25;
            --n-deep-line: rgb(231 234 232 / 0.12);
            --n-ease: cubic-bezier(0.22, 1, 0.36, 1);
            background: var(--n-paper);
        }

        .design-nordic { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; color: var(--n-ink); }
        .design-nordic .serif { font-family: 'Fraunces', ui-serif, Georgia, serif; font-optical-sizing: auto; }
        .design-nordic ::selection { background: var(--n-ink); color: var(--n-paper); }

        /* Header: dissolves into the sky at the top, condenses into frosted paper on scroll. */
        body:has(.design-nordic) > header { background: transparent; border-color: transparent; backdrop-filter: none; transition: background-color .6s var(--n-ease), border-color .6s var(--n-ease), backdrop-filter .6s; }
        body:has(.design-nordic) > header.landing-header--scrolled { background: rgb(242 243 240 / 0.8); border-color: var(--n-line); backdrop-filter: blur(16px) saturate(1.2); }
        body:has(.design-nordic) > header .h-16 > a:first-child, body:has(.design-nordic) > footer .md\:col-span-2 > a:first-child { font-family: 'Fraunces', serif; font-weight: 400; letter-spacing: -0.01em; }
        body:has(.design-nordic) > header .h-16 > a:first-child span, body:has(.design-nordic) > footer .md\:col-span-2 > a:first-child span { color: var(--n-ember); }
        body:has(.design-nordic) > footer .bg-stone-200 { background: var(--n-mist); }
        .design-nordic form input { background: rgb(242 243 240 / .06); border-color: var(--n-deep-line); }
        body:has(.design-nordic) > header nav a { color: var(--n-slate); }
        body:has(.design-nordic) > header nav a:hover { color: var(--n-ink); }
        body:has(.design-nordic) > footer { background: var(--n-paper); border-color: var(--n-line); }

        /* Hero plate: fades up from fog-grey so the image never pops in. */
        .nordic-plate { opacity: 0; transform: scale(1.06); transition: opacity 1.8s var(--n-ease), transform 2.8s var(--n-ease); }
        [data-plate-frame].is-loaded .nordic-plate { opacity: 1; transform: scale(1); }

        /* Mist drifts at two speeds; parallax between them gives the flat plate real depth. */
        .nordic-mist { position: absolute; inset: auto -20%; height: 34%; background:
            radial-gradient(40% 50% at 20% 50%, rgb(255 255 255 / 0.55), transparent 70%),
            radial-gradient(35% 45% at 65% 55%, rgb(255 255 255 / 0.45), transparent 70%),
            radial-gradient(30% 40% at 95% 45%, rgb(255 255 255 / 0.4), transparent 70%);
            filter: blur(18px); animation: nordic-drift 70s linear infinite alternate; }
        .nordic-mist--slow { animation-duration: 110s; animation-direction: alternate-reverse; opacity: .7; }
        @keyframes nordic-drift { from { translate: -6% 0; } to { translate: 6% 0; } }

        /* First light: a slow sun-glow breathing on the horizon. */
        .nordic-glow { background: radial-gradient(28% 22% at 50% 48%, rgb(255 226 178 / 0.55), transparent 70%); animation: nordic-breathe 9s ease-in-out infinite; mix-blend-mode: soft-light; }
        @keyframes nordic-breathe { 0%, 100% { opacity: .55; } 50% { opacity: 1; } }

        /* Headline lines rise out of a mask, like the ridge emerging from fog. */
        .nordic-line { display: block; overflow: hidden; padding-bottom: .08em; }
        .nordic-line > span { display: block; transform: translateY(105%); transition: transform 1.4s var(--n-ease); transition-delay: var(--delay, 0s); }
        .is-loaded .nordic-line > span, html:not(.js-motion) .nordic-line > span { transform: none; }

        /* The headline's reflection in the fjord, gently rippling. */
        .nordic-reflection { transform: scaleY(-1); opacity: 0; filter: url(#nordic-ripple) blur(1.4px); transition: opacity 2.4s var(--n-ease) .9s;
            -webkit-mask-image: linear-gradient(to top, rgb(0 0 0 / .4), transparent 60%); mask-image: linear-gradient(to top, rgb(0 0 0 / .4), transparent 60%); }
        .is-loaded .nordic-reflection { opacity: .16; }

        .nordic-fade { opacity: 0; translate: 0 14px; transition: opacity 1.2s var(--n-ease), translate 1.2s var(--n-ease); transition-delay: var(--delay, 0s); }
        .is-loaded .nordic-fade { opacity: 1; translate: 0 0; }

        .nordic-cue::after { content: ''; position: absolute; inset: 0; background: var(--n-ink); transform-origin: top; animation: nordic-cue 2.6s var(--n-ease) infinite; }
        @keyframes nordic-cue { 0% { transform: scaleY(0); } 50% { transform: scaleY(1); transform-origin: top; } 51% { transform-origin: bottom; } 100% { transform: scaleY(0); transform-origin: bottom; } }

        /* Scroll reveals. */
        html.js-motion .design-nordic [data-reveal] { opacity: 0; translate: 0 28px; transition: opacity 1.1s var(--n-ease), translate 1.1s var(--n-ease); transition-delay: calc(var(--i, 0) * 110ms); }
        html.js-motion .design-nordic [data-reveal].is-in { opacity: 1; translate: 0 0; }
        .nordic-rule { transform: scaleX(0); transform-origin: left; transition: transform 1.4s var(--n-ease); transition-delay: calc(var(--i, 0) * 110ms + 150ms); }
        .is-in .nordic-rule, html:not(.js-motion) .nordic-rule { transform: scaleX(1); }

        /* Statement reads itself in: each word brightens as scroll progresses through it. */
        .nordic-word { opacity: clamp(0.16, calc(var(--p, 1) * var(--n) * 1.15 - var(--w)), 1); transition: opacity .2s linear; }

        /* Pipeline: the visitor's scroll is the data moving through the stack. */
        .nordic-track { transform: scaleX(var(--p, 1)); transform-origin: left; }
        .nordic-packet { left: calc(var(--p, 1) * 100%); opacity: calc(1 - var(--p, 1) * var(--p, 1)); }
        .nordic-stage { opacity: .38; transition: opacity .6s var(--n-ease); }
        .nordic-stage .nordic-node { background: transparent; transition: background-color .6s var(--n-ease), box-shadow .6s var(--n-ease); }
        @for ($stage = 0; $stage < 4; $stage++)
            [data-stage="{{ $stage + 1 }}"] .nordic-stage:nth-child(-n+{{ $stage + 1 }}),
        @endfor
        [data-stage="4"] .nordic-stage { opacity: 1; }
        [data-stage="1"] .nordic-stage:nth-child(-n+1) .nordic-node, [data-stage="2"] .nordic-stage:nth-child(-n+2) .nordic-node,
        [data-stage="3"] .nordic-stage:nth-child(-n+3) .nordic-node, [data-stage="4"] .nordic-stage .nordic-node { background: var(--n-ember); box-shadow: 0 0 0 6px rgb(179 115 44 / .18); }

        /* Gallery glides sideways as you scroll down, so all four rooms are seen without extra interaction. */
        @media (min-width: 1024px) {
            .nordic-gallery-track { transform: translateX(calc((100vw - 100% - 6rem) * var(--p, 0))); will-change: transform; }
        }

        .nordic-link { background: linear-gradient(currentColor, currentColor) 0 100% / 0 1px no-repeat; transition: background-size .6s var(--n-ease); }
        .nordic-link:hover, .nordic-link:focus-visible { background-size: 100% 1px; }

        .nordic-work-image img { transition: transform 1.6s var(--n-ease), filter 1.6s var(--n-ease); filter: saturate(.85); }
        .nordic-work:hover .nordic-work-image img { transform: scale(1.035); filter: saturate(1); }

        @media (prefers-reduced-motion: reduce) {
            .design-nordic *, .design-nordic *::before, .design-nordic *::after { animation: none !important; transition: none !important; }
            .nordic-plate, .nordic-line > span, .nordic-fade { opacity: 1; transform: none; translate: none; }
        }
    </style>

    <svg width="0" height="0" class="absolute" aria-hidden="true">
        <filter id="nordic-ripple" x="0" y="0" width="100%" height="100%">
            <feTurbulence type="fractalNoise" baseFrequency="0.004 0.09" numOctaves="1" seed="4">
                <animate attributeName="baseFrequency" dur="14s" values="0.004 0.09;0.006 0.12;0.004 0.09" repeatCount="indefinite" />
            </feTurbulence>
            <feDisplacementMap in="SourceGraphic" scale="9" />
        </filter>
    </svg>

    {{-- =====================================================================
         HERO — Plate I: fjord at first light
    ====================================================================== --}}
    <section class="relative -mt-16 h-[100svh] min-h-[640px] max-h-[1000px] overflow-hidden bg-[#dfe3df]" data-plate-frame aria-labelledby="nordic-title">
        <div class="absolute inset-0" data-parallax="0.28">
            <div class="absolute -inset-6" data-tilt="14">
                <img src="{{ asset('images/home/hero-fjord.webp') }}"
                    srcset="{{ asset('images/home/hero-fjord-960.webp') }} 960w, {{ asset('images/home/hero-fjord.webp') }} 1536w"
                    sizes="100vw" width="1536" height="864" alt="" fetchpriority="high" decoding="async" data-plate
                    class="nordic-plate h-full w-full object-cover">
            </div>
        </div>
        <div class="nordic-glow pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute inset-0" data-parallax="0.12" aria-hidden="true">
            <div class="nordic-mist top-[38%]"></div>
            <div class="nordic-mist nordic-mist--slow top-[50%]"></div>
        </div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-[48%] bg-gradient-to-t from-[var(--n-paper)] via-[rgb(242_243_240/0.55)] to-transparent"></div>

        {{-- Headline sits on the horizon; its reflection sits in the water below. --}}
        <div class="absolute inset-x-0 bottom-[52.5%] px-6 text-center" data-parallax="-0.08">
            <p class="nordic-fade mb-6 text-[10px] font-medium uppercase tracking-[0.26em] text-[var(--n-slate)] sm:text-[11px] sm:tracking-[0.32em]" style="--delay: .2s">
                <span class="hidden sm:inline">Anders Learmonth &nbsp;·&nbsp; </span>Senior Software Engineer
            </p>
            <h1 id="nordic-title" class="serif text-[clamp(3.25rem,9.5vw,8.5rem)] font-light leading-[0.92] tracking-[-0.035em] text-[var(--n-ink)]">
                <span class="nordic-line"><span style="--delay: .35s">Software,</span></span>
                <span class="nordic-line"><span class="italic" style="--delay: .5s">considered.</span></span>
            </h1>
        </div>
        <div class="pointer-events-none absolute inset-x-0 top-[47.5%] px-6 text-center" aria-hidden="true">
            <p class="nordic-reflection serif text-[clamp(3.25rem,9.5vw,8.5rem)] font-light italic leading-[0.92] tracking-[-0.035em] text-[var(--n-ink)]">considered.</p>
        </div>

        <div class="absolute inset-x-0 bottom-0 px-6 pb-10 sm:pb-14">
            <div class="mx-auto flex max-w-6xl flex-col items-center gap-8 text-center lg:flex-row lg:items-end lg:justify-between lg:text-left">
                <div class="nordic-fade max-w-md" style="--delay: 1.1s">
                    <p class="text-[15px] leading-relaxed text-[var(--n-slate)]">
                        I build web products end to end, from the data model to the last easing curve, and the local AI tooling that lets one engineer ship like a team.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-6 lg:justify-start">
                        <a href="#work" class="group inline-flex items-center gap-3 rounded-full bg-[var(--n-ink)] px-6 py-3 text-sm font-medium text-[var(--n-paper)] transition-colors duration-500 hover:bg-[var(--n-slate)]">
                            Selected work
                            <svg class="h-3.5 w-3.5 transition-transform duration-500 group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-6-6m6 6l6-6" /></svg>
                        </a>
                        <a href="#lab" class="nordic-link text-sm font-medium text-[var(--n-ink)]">Inside the AI lab</a>
                    </div>
                </div>

                <div class="absolute bottom-10 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-3 lg:flex sm:bottom-14" aria-hidden="true">
                    <span class="text-[10px] uppercase tracking-[0.3em] text-[var(--n-mute)]">Scroll</span>
                    <span class="nordic-cue relative block h-12 w-px bg-[var(--n-line)]"></span>
                </div>

                <a href="#lab" class="nordic-fade group max-w-[15rem] text-[11px] leading-relaxed text-[var(--n-mute)] lg:text-right" style="--delay: 1.4s">
                    <span class="serif text-sm italic text-[var(--n-slate)]">Plate I. Fjord at first light.</span><br>
                    Generated on my own GPU with ComfyUI and SDXL Turbo. <span class="nordic-link text-[var(--n-slate)]">See how</span>
                </a>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         STATEMENT
    ====================================================================== --}}
    @php
        $statement = 'I like owning problems end to end, from shaping an idea to polishing the final experience. I value readable code, calm collaboration and clear written thinking, and I build the systems that let a team ship more with less friction.';
        $statementWords = explode(' ', $statement);
    @endphp
    <section class="px-6 py-28 lg:py-40">
        <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[12rem_1fr]">
            <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-[var(--n-mute)]" data-reveal>About</p>
            <p class="serif text-[clamp(1.75rem,3.4vw,2.9rem)] font-light leading-[1.22] tracking-[-0.015em]" data-progress style="--n: {{ count($statementWords) }}">
                @foreach ($statementWords as $word)
                    <span class="nordic-word" style="--w: {{ $loop->index }}">{{ $word }}</span>
                @endforeach
            </p>
        </div>
    </section>

    {{-- =====================================================================
         CAPABILITIES
    ====================================================================== --}}
    <section class="px-6 pb-28 lg:pb-40" aria-labelledby="nordic-capabilities">
        <div class="mx-auto max-w-6xl">
            <h2 id="nordic-capabilities" class="sr-only">How I can help</h2>
            <div class="grid gap-x-10 gap-y-14 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($capabilities as $capability)
                    <article data-reveal style="--i: {{ $loop->index }}">
                        <div class="nordic-rule mb-6 h-px bg-[var(--n-ink)]"></div>
                        <p class="serif mb-4 text-sm italic text-[var(--n-ember)]">{{ ['I', 'II', 'III', 'IV'][$loop->index] }}.</p>
                        <h3 class="serif text-2xl font-light tracking-[-0.01em]">{{ $capability['title'] }}</h3>
                        <p class="mt-4 text-sm leading-relaxed text-[var(--n-slate)]">{{ $capability['description'] }}</p>
                        <p class="mt-5 text-[11px] uppercase tracking-[0.18em] text-[var(--n-mute)]">{{ implode(' · ', $capability['stack']) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =====================================================================
         THE LAB — local AI stack
    ====================================================================== --}}
    <section id="lab" class="relative overflow-hidden bg-[var(--n-deep)] px-6 py-28 text-[var(--n-mist)] lg:py-36" aria-labelledby="nordic-lab-title">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[rgb(179_115_44/0.6)] to-transparent"></div>
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-10 lg:grid-cols-[12rem_1fr]">
                <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-[rgb(231_234_232/0.5)]" data-reveal>The lab</p>
                <div>
                    <h2 id="nordic-lab-title" class="serif max-w-3xl text-[clamp(2.25rem,5vw,4.25rem)] font-light leading-[1.02] tracking-[-0.03em]" data-reveal>
                        A local AI studio, <span class="italic text-[#e3b779]">quietly</span> running on one desk.
                    </h2>
                    <p class="mt-8 max-w-2xl text-[15px] leading-relaxed text-[rgb(231_234_232/0.68)]" data-reveal style="--i: 1">
                        Most of my time outside day-to-day delivery goes into making AI genuinely useful for building software: running models on my own hardware, wiring them into Laravel apps, and orchestrating many coding agents at once without losing track of the work.
                    </p>
                </div>
            </div>

            {{-- Pipeline --}}
            <div class="relative mt-24" data-progress data-stages="4">
                <div class="relative mb-12 hidden h-px bg-[var(--n-deep-line)] lg:block" aria-hidden="true">
                    <div class="nordic-track absolute inset-0 bg-gradient-to-r from-[rgb(179_115_44/0.2)] via-[#e3b779] to-[#e3b779]"></div>
                    <div class="nordic-packet absolute top-1/2 h-2 w-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#ffd9a3] shadow-[0_0_24px_6px_rgb(255_200_130/0.45)]"></div>
                </div>
                <ol class="grid gap-12 lg:grid-cols-4 lg:gap-10">
                    @foreach ($aiPipeline as $stage)
                        <li class="nordic-stage relative border-l border-[var(--n-deep-line)] pl-6 lg:border-l-0 lg:pl-0">
                            <span class="nordic-node absolute -left-[5px] top-1 h-2.5 w-2.5 rounded-full border border-[#e3b779] lg:-top-[3.4rem] lg:left-0"></span>
                            <p class="text-[11px] uppercase tracking-[0.22em] text-[#e3b779]">{{ $stage['label'] }}</p>
                            <h3 class="serif mt-3 text-xl font-light leading-snug">{{ $stage['title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[rgb(231_234_232/0.6)]">{{ $stage['description'] }}</p>
                            <p class="mt-4 text-[11px] uppercase tracking-[0.16em] text-[rgb(231_234_232/0.4)]">{{ implode(' · ', $stage['tools']) }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Life OS --}}
            <div class="mt-32 grid gap-12 lg:grid-cols-[1fr_1.1fr] lg:items-end">
                <div data-reveal>
                    <p class="serif text-sm italic text-[#e3b779]">Life OS</p>
                    <h3 class="serif mt-3 text-[clamp(1.9rem,3.5vw,3rem)] font-light leading-[1.05] tracking-[-0.025em]">A 3D world for running a team of agents.</h3>
                    <p class="mt-6 max-w-lg text-[15px] leading-relaxed text-[rgb(231_234_232/0.65)]">
                        A first-person office where each pod of desks is assigned to a project. Every desk runs its own agent session in its own git worktree, so agents never trip over each other's changes, and progress lands on kanban boards inside the world in real time.
                    </p>
                </div>
                <dl class="grid grid-cols-3 gap-6 border-t border-[var(--n-deep-line)] pt-8">
                    @foreach ($lifeOsStats as $stat)
                        <div data-reveal style="--i: {{ $loop->index }}">
                            <dd class="serif text-[clamp(2.5rem,5vw,4.5rem)] font-light leading-none tracking-[-0.04em] text-[var(--n-paper)]" @if($stat['countable']) data-count="{{ $stat['value'] }}" @endif>{{ $stat['value'] }}</dd>
                            <dt class="mt-3 text-xs leading-snug text-[rgb(231_234_232/0.5)]">{{ $stat['label'] }}</dt>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <div class="mt-16 overflow-hidden lg:mt-20" data-progress>
            <div class="nordic-gallery-track flex snap-x snap-mandatory gap-6 overflow-x-auto px-6 pb-4 lg:w-max lg:overflow-visible lg:px-[max(1.5rem,calc((100vw-72rem)/2))]">
                @foreach ($lifeOsImages as $image)
                    <figure class="w-[82vw] shrink-0 snap-center sm:w-[60vw] lg:w-[38rem]">
                        <div class="overflow-hidden rounded-sm bg-[#0c1319]">
                            <img src="{{ asset('images/now/'.$image['file']) }}" alt="{{ $image['caption'] }}" width="1600" height="900" loading="lazy" decoding="async" class="aspect-video w-full object-cover opacity-90">
                        </div>
                        <figcaption class="mt-4 flex gap-4 text-xs leading-relaxed text-[rgb(231_234_232/0.5)]">
                            <span class="serif italic text-[#e3b779]">{{ ['i', 'ii', 'iii', 'iv'][$loop->index] }}.</span>
                            {{ $image['caption'] }}
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>

        {{-- How the hero plate was made --}}
        <div class="mx-auto mt-28 max-w-6xl">
            <div class="grid gap-12 border-t border-[var(--n-deep-line)] pt-14 lg:grid-cols-[12rem_1fr_1fr]">
                <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-[rgb(231_234_232/0.5)]" data-reveal>Plate I</p>
                <div data-reveal>
                    <h3 class="serif text-[clamp(1.6rem,2.6vw,2.2rem)] font-light leading-[1.1] tracking-[-0.02em]">The photograph at the top of this page was never taken.</h3>
                    <p class="mt-5 max-w-md text-sm leading-relaxed text-[rgb(231_234_232/0.6)]">
                        It was generated locally in ComfyUI on an RTX 2060 Super, then brought to life in the browser with drifting mist, first light and a rippling reflection. About forty seconds of GPU time, no stock library, no per-image fee.
                    </p>
                </div>
                <ol class="relative space-y-5 border-l border-[var(--n-deep-line)] pl-6" data-reveal style="--i: 1">
                    @foreach ($heroRecipe as $step)
                        <li class="relative">
                            <span class="absolute -left-[1.72rem] top-1.5 h-2 w-2 rounded-full bg-[var(--n-deep)] ring-1 ring-[#e3b779]"></span>
                            <p class="text-sm text-[var(--n-mist)]">{{ $step['node'] }}</p>
                            <p class="font-mono text-[11px] text-[rgb(231_234_232/0.45)]">{{ $step['detail'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         SELECTED WORK
    ====================================================================== --}}
    <section id="work" class="px-6 py-28 lg:py-40" aria-labelledby="nordic-work-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-20 grid gap-10 lg:grid-cols-[12rem_1fr]">
                <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-[var(--n-mute)]" data-reveal>Selected work</p>
                <h2 id="nordic-work-title" class="serif text-[clamp(2.25rem,5vw,4.25rem)] font-light leading-[1.02] tracking-[-0.03em]" data-reveal>Things that shipped, <span class="italic">and kept working.</span></h2>
            </div>

            <div class="divide-y divide-[var(--n-line)] border-y border-[var(--n-line)]">
                @forelse ($featuredProjects as $project)
                    @php $projectImage = $project->uploadedImage?->base64_data ?? $project->image; @endphp
                    <article class="nordic-work grid gap-8 py-12 lg:grid-cols-[12rem_1fr_22rem] lg:gap-10" data-reveal>
                        <p class="serif text-5xl font-light italic leading-none text-[var(--n-mute)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                        <div>
                            <h3 class="serif text-3xl font-light tracking-[-0.015em]">{{ $project->title }}</h3>
                            <p class="mt-4 max-w-xl text-[15px] leading-relaxed text-[var(--n-slate)]">{{ $project->description }}</p>
                            @if ($project->technologies)
                                <p class="mt-5 text-[11px] uppercase tracking-[0.18em] text-[var(--n-mute)]">{{ implode(' · ', array_slice($project->technologies, 0, 5)) }}</p>
                            @endif
                            <div class="mt-6 flex gap-6 text-sm font-medium">
                                @if ($project->url)
                                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="nordic-link">Visit ↗</a>
                                @endif
                                @if ($project->github_url)
                                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="nordic-link">Source ↗</a>
                                @endif
                            </div>
                        </div>
                        <div class="nordic-work-image aspect-[4/3] overflow-hidden rounded-sm bg-[var(--n-mist)]">
                            @if ($projectImage)
                                <img src="{{ $projectImage }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-[var(--n-mist)] to-[#d5dbd9]">
                                    <span class="serif text-6xl font-light italic text-[var(--n-mute)]">{{ mb_substr($project->title, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="py-16 text-[var(--n-mute)]">New work is being written up.</p>
                @endforelse
            </div>

            <a href="{{ route('projects') }}" class="nordic-link mt-10 inline-block text-sm font-medium" data-reveal>All projects →</a>
        </div>
    </section>

    {{-- =====================================================================
         JOURNAL
    ====================================================================== --}}
    <section class="px-6 pb-28 lg:pb-40" aria-labelledby="nordic-journal-title">
        <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[12rem_1fr]">
            <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-[var(--n-mute)]" data-reveal>Journal</p>
            <div>
                <h2 id="nordic-journal-title" class="sr-only">Writing</h2>
                <ul class="divide-y divide-[var(--n-line)] border-y border-[var(--n-line)]">
                    @forelse ($recentBlogPosts as $post)
                        <li data-reveal style="--i: {{ $loop->index }}">
                            <a href="{{ route('blog.post', $post) }}" class="group grid gap-2 py-7 sm:grid-cols-[8rem_1fr_auto] sm:items-baseline sm:gap-8">
                                <time datetime="{{ $post->published_at->toDateString() }}" class="text-xs text-[var(--n-mute)]">{{ $post->published_at->format('M Y') }}</time>
                                <span>
                                    <span class="serif text-2xl font-light tracking-[-0.01em] transition-colors duration-500 group-hover:text-[var(--n-ember)]">{{ $post->title }}</span>
                                    @if ($post->excerpt)
                                        <span class="mt-1 block text-sm text-[var(--n-slate)]">{{ Str::limit($post->excerpt, 120) }}</span>
                                    @endif
                                </span>
                                <span class="hidden text-[var(--n-mute)] transition-transform duration-500 group-hover:translate-x-1 sm:block" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @empty
                        <li class="py-7 text-[var(--n-mute)]">The first entries are on their way.</li>
                    @endforelse
                </ul>
                <a href="{{ route('blog') }}" class="nordic-link mt-10 inline-block text-sm font-medium" data-reveal>Read the journal →</a>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         CLOSING
    ====================================================================== --}}
    <section class="border-t border-[var(--n-line)] px-6 py-28 lg:py-36">
        <div class="mx-auto grid max-w-6xl gap-16 lg:grid-cols-[1.3fr_1fr] lg:items-end">
            <div data-reveal>
                <h2 class="serif text-[clamp(2.5rem,6vw,5.25rem)] font-light leading-[0.98] tracking-[-0.035em]">
                    Good work usually starts<br class="hidden sm:block"> with a <span class="italic">conversation.</span>
                </h2>
                <a href="{{ route('contact') }}" class="group mt-10 inline-flex items-center gap-3 text-base font-medium">
                    <span class="nordic-link">Write to me</span>
                    <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
                </a>
            </div>
            <div class="rounded-sm bg-[var(--n-deep)] p-8" data-reveal style="--i: 1">
                <p class="serif text-xl font-light text-[var(--n-mist)]">Occasional letters from the lab.</p>
                <p class="mb-6 mt-2 text-sm text-[rgb(231_234_232/0.55)]">New writing, projects and experiments. Never more than worth opening.</p>
                @livewire('newsletter-signup')
            </div>
        </div>
    </section>
</div>
