<div>
    <section class="overflow-hidden border-b border-stone-100 bg-stone-50">
        <div class="mx-auto grid max-w-6xl gap-12 px-6 py-16 lg:grid-cols-[1.2fr_0.8fr] lg:items-center lg:py-24">
            <div>
                <div class="mb-6 flex items-center gap-3">
                    <span class="inline-block h-0.5 w-8 bg-amber-400"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Now / Brag page</span>
                </div>

                <h1 class="mb-6 text-[clamp(2.75rem,7vw,4.75rem)] leading-[0.95] font-black tracking-tight text-stone-900">
                    What I am building,<br>
                    improving, and bringing<br>
                    to a team right now.
                </h1>

                <p class="max-w-2xl text-lg leading-relaxed text-stone-600">
                    This is a living snapshot for hiring managers, collaborators, and anyone curious about the kind
                    of work I do best: thoughtful Laravel builds, strong written communication, and steady
                    professional growth through practical shipping and continuous learning.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-stone-900 px-6 py-3.5 text-sm font-bold text-white transition-colors hover:bg-stone-700">
                        Start a conversation
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                    <a href="{{ route('blog') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-stone-300 bg-white px-6 py-3.5 text-sm font-bold text-stone-700 transition-colors hover:border-stone-500 hover:text-stone-900">
                        Read the blog
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:col-span-2">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Professional snapshot</p>
                    <p class="mt-3 text-sm leading-relaxed text-stone-600">
                        I care about shipping work that is useful, well considered, and easy to maintain. I am at my
                        best where product thinking, code quality, and communication all matter.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Published posts</p>
                    <p class="mt-3 text-3xl font-black tracking-tight text-stone-900">{{ number_format($publishedPostsCount) }}</p>
                    <p class="mt-2 text-sm text-stone-500">Technical writing that supports how I work and how I learn.</p>
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Total blog views</p>
                    <p class="mt-3 text-3xl font-black tracking-tight text-stone-900">{{ number_format($totalBlogViews) }}</p>
                    <p class="mt-2 text-sm text-stone-500">Useful social proof if you want this page to double as a living CV.</p>
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Current projects</p>
                    <p class="mt-3 text-3xl font-black tracking-tight text-stone-900">{{ number_format($currentProjectsCount) }}</p>
                    <p class="mt-2 text-sm text-stone-500">A quick view of what I am actively pushing forward right now.</p>
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Recent completions</p>
                    <p class="mt-3 text-3xl font-black tracking-tight text-stone-900">{{ number_format($recentlyCompletedProjectsCount) }}</p>
                    <p class="mt-2 text-sm text-stone-500">Recent shipped work that helps show delivery momentum.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-10 max-w-3xl">
                <p class="mb-2 text-xs font-bold uppercase tracking-widest text-stone-400">Current focus</p>
                <h2 class="text-3xl font-black tracking-tight text-stone-900">What I am working on now</h2>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="rounded-3xl border border-amber-100 bg-amber-50 p-7">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Active work</p>

                    @if ($currentProjects->isNotEmpty())
                        <div class="mt-5 grid gap-4">
                            @foreach ($currentProjects as $project)
                                <div class="rounded-2xl border border-amber-200 bg-white p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-stone-900">{{ $project->title }}</h3>
                                            <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $project->description }}</p>
                                        </div>
                                        <span class="inline-flex shrink-0 items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            In progress
                                        </span>
                                    </div>

                                    @if ($project->technologies)
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach (array_slice($project->technologies, 0, 4) as $technology)
                                                <span class="rounded-full border border-stone-200 bg-stone-50 px-3 py-1 text-xs font-semibold text-stone-600">
                                                    {{ $technology }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm leading-relaxed text-stone-600">
                            Add your current client work, product focus, or most important initiative here once you
                            want to share it publicly.
                        </p>
                    @endif
                </div>

                <div class="grid gap-4">
                    <div class="rounded-3xl border border-stone-200 bg-stone-50 p-7">
                        <p class="text-xs font-bold uppercase tracking-widest text-stone-400">How I work</p>
                        <ul class="mt-4 grid gap-3 text-sm leading-relaxed text-stone-600">
                            <li class="rounded-2xl bg-white px-4 py-3">I like owning problems end to end, from shaping an idea to polishing the final UX.</li>
                            <li class="rounded-2xl bg-white px-4 py-3">I value readable code, calm collaboration, and clear written thinking.</li>
                            <li class="rounded-2xl bg-white px-4 py-3">I am intentionally strengthening both product delivery and data fluency, especially with SQL.</li>
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-stone-200 bg-stone-900 p-7 text-white">
                        <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Why this page exists</p>
                        <p class="mt-4 text-sm leading-relaxed text-stone-300">
                            It is a faster way for a hiring manager to understand what I am doing now, how I think
                            about my craft, and where I am deliberately investing in growth.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-stone-100 bg-stone-50 py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-10 max-w-3xl">
                <p class="mb-2 text-xs font-bold uppercase tracking-widest text-stone-400">Recently completed</p>
                <h2 class="text-3xl font-black tracking-tight text-stone-900">Recent delivery and ongoing maintenance</h2>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-stone-200 bg-white p-7">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">Shipped work</p>

                    @if ($recentlyCompletedProjects->isNotEmpty())
                        <div class="mt-5 grid gap-4">
                            @foreach ($recentlyCompletedProjects as $project)
                                <div class="rounded-2xl border border-stone-100 bg-stone-50 p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-stone-900">{{ $project->title }}</h3>
                                            <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $project->description }}</p>
                                        </div>
                                        <span class="inline-flex shrink-0 items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Completed
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm leading-relaxed text-stone-600">
                            Add a few finished projects or role wins here to make your delivery track record even stronger.
                        </p>
                    @endif
                </div>

                <div class="rounded-3xl border border-stone-200 bg-white p-7">
                    <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Professional strengths</p>
                    <div class="mt-5 grid gap-4">
                        @foreach ($strengths as $strength)
                            <div class="rounded-2xl border border-stone-100 bg-stone-50 p-5">
                                <h3 class="text-lg font-bold text-stone-900">{{ $strength['title'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $strength['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-10 max-w-3xl">
                <p class="mb-2 text-xs font-bold uppercase tracking-widest text-stone-400">Learning and qualifications</p>
                <h2 class="text-3xl font-black tracking-tight text-stone-900">Courses and structured learning</h2>
                <p class="mt-4 text-sm leading-relaxed text-stone-600">
                    This section is designed to grow over time. It already includes your recent SQL study and makes
                    room for the Laracasts and other extracurricular learning you want to surface.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-widest text-stone-500">Course</th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-widest text-stone-500">Provider</th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-widest text-stone-500">Status</th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-widest text-stone-500">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($courseEntries as $entry)
                                <tr class="align-top">
                                    <td class="px-5 py-4 text-sm font-semibold text-stone-900">{{ $entry['course'] }}</td>
                                    <td class="px-5 py-4 text-sm text-stone-600">{{ $entry['provider'] }}</td>
                                    <td class="px-5 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-700">
                                            {{ $entry['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm leading-relaxed text-stone-600">{{ $entry['notes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-stone-900 py-16 text-white lg:py-20">
        <div class="mx-auto grid max-w-6xl gap-8 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-widest text-stone-400">Still to personalise</p>
                <h2 class="text-3xl font-black tracking-tight">A few high-value details to fill in next.</h2>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-stone-300">
                    The page is live and professional already, but these additions would make it even more convincing
                    as a hiring-manager-facing summary.
                </p>
            </div>

            <div class="grid gap-4">
                @foreach ($fillInPrompts as $prompt)
                    <div class="rounded-2xl border border-stone-700 bg-stone-800 px-5 py-4 text-sm leading-relaxed text-stone-200">
                        {{ $prompt }}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
