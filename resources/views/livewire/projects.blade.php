<div>
    <x-aurora.page-header path="~/projects" eyebrow="ls -la"
        description="A collection of things I've built, from side projects to open-source tools.">
        <x-slot:title>Shipped, and <span class="aurora-gradient-text">still running.</span></x-slot:title>

        <dl class="flex flex-wrap gap-3 font-mono text-xs">
            <div class="aurora-glass flex items-center gap-2 rounded-full px-4 py-2">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-glow-violet"></span>
                <dt class="text-mute">in progress</dt>
                <dd class="text-ink">{{ $inProgressProjects->count() }}</dd>
            </div>
            <div class="aurora-glass flex items-center gap-2 rounded-full px-4 py-2">
                <span class="h-1.5 w-1.5 rounded-full bg-glow-teal"></span>
                <dt class="text-mute">shipped</dt>
                <dd class="text-ink">{{ $completedProjects->count() }}</dd>
            </div>
        </dl>
    </x-aurora.page-header>

    @if ($inProgressProjects->isNotEmpty())
        <section class="px-6 pt-16 lg:pt-24" aria-labelledby="in-progress-title">
            <div class="mx-auto max-w-6xl">
                <div class="mb-10" data-reveal>
                    <p class="mb-4 font-mono text-xs text-glow-violet">// currently building</p>
                    <h2 id="in-progress-title" class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">In Progress</h2>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($inProgressProjects as $project)
                        <x-aurora.project-card :project="$project" :index="$loop->index % 3" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="px-6 py-16 lg:py-24" aria-labelledby="completed-title">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10" data-reveal>
                <p class="aurora-eyebrow mb-4">// shipped</p>
                <h2 id="completed-title" class="text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">Completed Projects</h2>
            </div>

            @if ($completedProjects->isNotEmpty())
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($completedProjects->unique('id') as $project)
                        <x-aurora.project-card :project="$project" :index="$loop->index % 3" />
                    @endforeach
                </div>
            @else
                <div class="aurora-glass rounded-2xl px-6 py-16 text-center">
                    <p class="font-mono text-sm text-dim">// building</p>
                    <p class="mt-3 text-lg text-mute">Projects coming soon — check back!</p>
                </div>
            @endif
        </div>
    </section>

    <section class="border-t border-edge px-6 py-20 lg:py-24">
        <div class="mx-auto flex max-w-6xl flex-col gap-8 lg:flex-row lg:items-end lg:justify-between" data-reveal>
            <div>
                <p class="aurora-eyebrow mb-4">// behind the work</p>
                <h2 class="max-w-2xl text-[clamp(1.75rem,3.4vw,2.75rem)] font-medium leading-[1.02] tracking-[-0.035em]">Most of these were built alongside a fleet of local AI agents.</h2>
            </div>
            <a href="{{ route('home') }}#lab" class="aurora-cta shrink-0">Tour the AI lab <span aria-hidden="true">→</span></a>
        </div>
    </section>
</div>
