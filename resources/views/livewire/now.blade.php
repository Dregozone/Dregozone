<div>
    <x-aurora.page-header path="~/now" eyebrow="Now / Brag page"
        description="A living snapshot for hiring managers, collaborators, and anyone curious about the kind of work I do best: thoughtful Laravel builds, AI-powered workflows that multiply what one developer can ship, strong written communication, and steady growth through continuous learning.">
        <x-slot:title>What I'm building, improving, and bringing to a team <span class="aurora-gradient-text">right now.</span></x-slot:title>

        <div class="flex flex-wrap items-center gap-6">
            <a href="{{ route('contact') }}" class="aurora-cta">Start a conversation <span aria-hidden="true">→</span></a>
            <a href="{{ route('blog') }}" class="group inline-flex items-center gap-2 text-sm font-medium text-mute transition-colors hover:text-ink">
                Read the blog <span class="transition-transform duration-500 group-hover:translate-x-1" aria-hidden="true">→</span>
            </a>
        </div>
    </x-aurora.page-header>

    {{-- =====================================================================
         SNAPSHOT
    ====================================================================== --}}
    <section class="px-6 py-16 lg:py-20" aria-label="Professional snapshot">
        <div class="mx-auto grid max-w-6xl gap-4 lg:grid-cols-[1.1fr_2fr]">
            <div class="aurora-glass flex flex-col justify-between rounded-2xl p-6" data-reveal>
                <p class="aurora-eyebrow">// Professional snapshot</p>
                <p class="mt-6 text-lg leading-relaxed text-ink">
                    I care about shipping work that is useful, well considered, and easy to maintain. I am at my best where product thinking, code quality, and communication all matter.
                </p>
            </div>
            <dl class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Published posts', 'value' => $publishedPostsCount, 'note' => 'Technical writing that supports how I work and how I learn.'],
                    ['label' => 'Total blog views', 'value' => $totalBlogViews, 'note' => 'Reads across everything I have published so far.'],
                    ['label' => 'Current projects', 'value' => $currentProjectsCount, 'note' => 'A quick view of what I am actively pushing forward right now.'],
                    ['label' => 'Recent completions', 'value' => $recentlyCompletedProjectsCount, 'note' => 'Recent shipped work that helps show delivery momentum.'],
                ] as $stat)
                    <div class="aurora-glass flex flex-col rounded-2xl p-5" data-reveal style="--i: {{ $loop->index + 1 }}">
                        <dt class="font-mono text-[11px] text-dim">{{ $stat['label'] }}</dt>
                        <dd class="mt-4 text-4xl font-medium tracking-[-0.04em] text-ink" data-count="{{ (int) $stat['value'] }}">{{ number_format($stat['value']) }}</dd>
                        <p class="mt-3 text-xs leading-relaxed text-mute">{{ $stat['note'] }}</p>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- =====================================================================
         AI FOCUS
    ====================================================================== --}}
    <section class="relative overflow-hidden border-y border-edge px-6 py-24 lg:py-32" aria-labelledby="now-ai-title">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_40%_at_70%_0%,rgb(160_141_255/0.12),transparent_70%),radial-gradient(50%_40%_at_10%_30%,rgb(63_230_192/0.08),transparent_70%)]"></div>
        <div class="aurora-grid pointer-events-none absolute inset-0 opacity-40 [mask-image:radial-gradient(70%_60%_at_50%_30%,#000,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end" data-reveal>
                <div>
                    <p class="aurora-eyebrow mb-4">// main focus right now</p>
                    <h2 id="now-ai-title" class="text-[clamp(2.1rem,5vw,4rem)] font-medium leading-[0.98] tracking-[-0.045em]">
                        AI-powered development, <span class="aurora-gradient-text">locally and at scale.</span>
                    </h2>
                </div>
                <p class="leading-relaxed text-mute">
                    Most of my time outside day-to-day delivery goes into making AI genuinely useful for building software: running models on my own hardware, wiring them into Laravel apps, and orchestrating many coding agents at once without losing track of the work.
                </p>
            </div>

            <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($aiHighlights as $highlight)
                    <article class="aurora-glass flex flex-col rounded-2xl p-6" data-spotlight data-reveal style="--i: {{ $loop->index }}">
                        <p class="font-mono text-xs text-dim">0x0{{ $loop->iteration }}</p>
                        <h3 class="mt-8 text-lg font-medium leading-snug tracking-[-0.015em]">{{ $highlight['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-mute">{{ $highlight['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-24 grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div data-reveal>
                    <p class="mb-4 font-mono text-xs text-glow-violet">// Life OS</p>
                    <h3 class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">A 3D world for running a team of agents</h3>
                    <p class="mt-6 leading-relaxed text-mute">
                        To handle many agents at once, I built Life OS: a first-person 3D world with an office where each pod of desks is assigned to a project. Every desk runs its own agent session in its own git worktree, so agents never trip over each other's changes. Outstanding work and progress are tracked on custom-built kanban boards inside the world, updating in real time.
                    </p>
                    <dl class="mt-10 grid grid-cols-3 gap-3">
                        @foreach ($lifeOsStats as $stat)
                            <div class="aurora-glass rounded-xl p-4" data-reveal style="--i: {{ $loop->index }}">
                                <dd class="text-3xl font-medium tracking-[-0.04em] text-ink" @if (is_numeric($stat['value'])) data-count="{{ $stat['value'] }}" @endif>{{ $stat['value'] }}</dd>
                                <dt class="mt-2 text-xs leading-snug text-mute">{{ $stat['label'] }}</dt>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div data-progress>
                    <div class="aurora-wall grid gap-3 sm:grid-cols-2">
                        @foreach ($lifeOsImages as $image)
                            <figure @class(['group relative overflow-hidden rounded-xl border border-edge bg-night', 'sm:col-span-2' => $loop->first])>
                                <img src="{{ asset('images/now/'.$image['file']) }}" alt="{{ $image['caption'] }}" width="1600" height="900" loading="lazy" decoding="async" class="aspect-video w-full object-cover opacity-80 transition-opacity duration-700 group-hover:opacity-100">
                                <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-void/90 to-transparent px-4 pb-3 pt-10 font-mono text-[11px] text-mute">{{ $image['caption'] }}</figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         CURRENT FOCUS
    ====================================================================== --}}
    <section class="px-6 py-20 lg:py-28" aria-labelledby="now-focus-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10" data-reveal>
                <p class="aurora-eyebrow mb-4">// Current focus</p>
                <h2 id="now-focus-title" class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">What I am working on now</h2>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="aurora-glass rounded-2xl p-6" data-reveal>
                    <p class="mb-5 font-mono text-xs text-glow-violet">// Active work</p>
                    @if ($currentProjects->isNotEmpty())
                        <div class="grid gap-3">
                            @foreach ($currentProjects as $project)
                                <div class="rounded-xl border border-edge bg-void/40 p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="text-lg font-medium tracking-[-0.015em]">{{ $project->title }}</h3>
                                        <span class="flex shrink-0 items-center gap-1.5 font-mono text-[11px] text-glow-violet">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-glow-violet"></span>In progress
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm leading-relaxed text-mute">{{ $project->description }}</p>
                                    @if ($project->technologies)
                                        <div class="mt-4 flex flex-wrap gap-1.5">
                                            @foreach (array_slice($project->technologies, 0, 4) as $technology)
                                                <span class="aurora-chip">{{ $technology }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm leading-relaxed text-mute">New work is being scoped; check back soon.</p>
                    @endif
                </div>

                <div class="grid gap-4">
                    <div class="aurora-glass rounded-2xl p-6" data-reveal style="--i: 1">
                        <p class="mb-5 font-mono text-xs text-glow-teal">// How I work</p>
                        <ul class="grid gap-3 text-sm leading-relaxed text-mute">
                            @foreach ([
                                'I like owning problems end to end, from shaping an idea to polishing the final UX.',
                                'I value readable code, calm collaboration, and clear written thinking.',
                                'I am intentionally strengthening both product delivery and data fluency, especially with SQL.',
                                'I look for repeated work to automate, and build the systems that let a team ship more with less friction.',
                            ] as $principle)
                                <li class="flex gap-3"><span class="mt-2.5 h-px w-3 shrink-0 bg-glow-teal"></span>{{ $principle }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="rounded-2xl border border-edge bg-[radial-gradient(80%_100%_at_100%_0%,rgb(160_141_255/0.14),transparent)] p-6" data-reveal style="--i: 2">
                        <p class="mb-3 font-mono text-xs text-dim">// Why this page exists</p>
                        <p class="text-sm leading-relaxed text-mute">
                            It is a faster way for a hiring manager to understand what I am doing now, how I think about my craft, and where I am deliberately investing in growth.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         DELIVERY & STRENGTHS
    ====================================================================== --}}
    <section class="border-t border-edge px-6 py-20 lg:py-28" aria-labelledby="now-delivery-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10" data-reveal>
                <p class="aurora-eyebrow mb-4">// Recently completed</p>
                <h2 id="now-delivery-title" class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">Recent delivery and ongoing maintenance</h2>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="aurora-glass rounded-2xl p-6" data-reveal>
                    <p class="mb-5 font-mono text-xs text-glow-teal">// Shipped work</p>
                    @if ($recentlyCompletedProjects->isNotEmpty())
                        <ol class="relative grid gap-3 border-l border-edge pl-5">
                            @foreach ($recentlyCompletedProjects as $project)
                                <li class="relative">
                                    <span class="absolute -left-[1.6rem] top-2 h-2 w-2 rounded-full bg-glow-teal shadow-[0_0_10px_rgb(63_230_192/0.7)]"></span>
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-medium">{{ $project->title }}</h3>
                                        <span class="shrink-0 font-mono text-[11px] text-glow-teal">Completed</span>
                                    </div>
                                    <p class="mt-1 text-sm leading-relaxed text-mute">{{ $project->description }}</p>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-sm leading-relaxed text-mute">Recent deliveries are being written up.</p>
                    @endif
                </div>

                <div class="aurora-glass rounded-2xl p-6" data-reveal style="--i: 1">
                    <p class="mb-5 font-mono text-xs text-glow-cyan">// Professional strengths</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($strengths as $strength)
                            <div class="rounded-xl border border-edge bg-void/40 p-4">
                                <h3 class="font-medium leading-snug">{{ $strength['title'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-mute">{{ $strength['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         LEARNING
    ====================================================================== --}}
    <section class="border-t border-edge px-6 py-20 lg:py-28" aria-labelledby="now-learning-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10 grid gap-6 lg:grid-cols-[1fr_1fr] lg:items-end" data-reveal>
                <div>
                    <p class="aurora-eyebrow mb-4">// Learning and qualifications</p>
                    <h2 id="now-learning-title" class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">Courses and structured learning</h2>
                </div>
                <p class="leading-relaxed text-mute">What I am studying alongside my work right now, with a strong lean towards database performance, Livewire, and AI.</p>
            </div>

            <div class="aurora-glass overflow-hidden rounded-2xl" data-reveal>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-edge font-mono text-[11px] text-dim">
                            <tr>
                                <th scope="col" class="px-5 py-4 text-left font-normal">course</th>
                                <th scope="col" class="px-5 py-4 text-left font-normal">provider</th>
                                <th scope="col" class="w-56 px-5 py-4 text-left font-normal">status</th>
                                <th scope="col" class="px-5 py-4 text-left font-normal">notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-edge">
                            @foreach ($courseEntries as $entry)
                                @php
                                    $progress = preg_match('/(\d+)%/', $entry['status'], $matches)
                                        ? (int) $matches[1] / 100
                                        : (str_contains(strtolower($entry['status']), 'completed') ? 1 : null);
                                @endphp
                                <tr class="align-top transition-colors hover:bg-white/[0.02]">
                                    <td class="px-5 py-4 font-medium text-ink">{{ $entry['course'] }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-mute">{{ $entry['provider'] }}</td>
                                    <td class="px-5 py-4">
                                        <span @class(['font-mono text-[11px]', 'text-glow-teal' => $progress === 1, 'text-mute' => $progress !== 1])>{{ $entry['status'] }}</span>
                                        @if ($progress !== null)
                                            <span class="mt-2 block h-1 overflow-hidden rounded-full bg-edge" aria-hidden="true">
                                                <span class="aurora-gradient aurora-meter block h-full rounded-full" style="--value: {{ $progress }}"></span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 leading-relaxed text-mute">{{ $entry['notes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         READING & TINKERING
    ====================================================================== --}}
    <section class="border-t border-edge px-6 py-20 lg:py-28">
        <div class="mx-auto grid max-w-6xl gap-4 lg:grid-cols-2">
            <div class="aurora-glass rounded-2xl p-6" data-reveal>
                <p class="aurora-eyebrow">// Reading</p>
                <h2 class="mt-3 text-2xl font-medium tracking-[-0.03em]">On my bookshelf</h2>
                <div class="mt-6 grid gap-3">
                    @foreach ($readingList as $book)
                        <div class="rounded-xl border border-edge bg-void/40 p-4">
                            <h3 class="font-medium">{{ $book['title'] }}</h3>
                            <p class="font-mono text-[11px] text-glow-ember">{{ $book['author'] }}</p>
                            <p class="mt-2 text-sm leading-relaxed text-mute">{{ $book['reason'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="aurora-glass rounded-2xl p-6" data-reveal style="--i: 1">
                <p class="aurora-eyebrow">// Side projects</p>
                <h2 class="mt-3 text-2xl font-medium tracking-[-0.03em]">Hardware and tinkering</h2>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach ($sideProjects as $sideProject)
                        <div class="rounded-xl border border-edge bg-void/40 p-4">
                            <h3 class="font-medium">{{ $sideProject['title'] }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-mute">{{ $sideProject['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         GOALS
    ====================================================================== --}}
    <section class="relative overflow-hidden border-t border-edge px-6 py-24 lg:py-32" data-plate-frame>
        <canvas wire:ignore class="aurora-canvas pointer-events-none absolute inset-0 [--aurora-opacity:0.45]" data-aurora data-envelope-start="0.1" data-envelope-end="0.6" aria-hidden="true"></canvas>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-void via-transparent to-void"></div>

        <div class="relative mx-auto grid max-w-6xl gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div data-reveal>
                <p class="aurora-eyebrow mb-4">// Goals</p>
                <h2 class="text-[clamp(2rem,4.4vw,3.5rem)] font-medium leading-[1] tracking-[-0.04em]">Where I am heading <span class="aurora-gradient-text">next.</span></h2>
                <p class="mt-6 max-w-xl leading-relaxed text-mute">
                    I am most energised by making teams faster: better workflows, less repetitive work, and systems that let good people focus on what matters.
                </p>
                <a href="{{ route('contact') }}" class="aurora-cta mt-10">Talk to me about a role <span aria-hidden="true">→</span></a>
            </div>

            <ol class="relative grid gap-4 border-l border-edge pl-6">
                @foreach ($goals as $goal)
                    <li class="aurora-glass relative rounded-2xl p-5" data-reveal style="--i: {{ $loop->index }}">
                        <span class="absolute -left-[1.95rem] top-6 h-2.5 w-2.5 rounded-full border border-glow-teal bg-void shadow-[0_0_12px_rgb(63_230_192/0.6)]"></span>
                        <p class="font-mono text-[11px] text-glow-teal">{{ $goal['label'] }}</p>
                        <h3 class="mt-1 text-lg font-medium">{{ $goal['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-mute">{{ $goal['description'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
</div>
