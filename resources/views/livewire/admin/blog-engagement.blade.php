<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <flux:heading size="xl">Blog Engagement</flux:heading>
            <flux:text class="mt-2 max-w-3xl text-sm sm:text-base">
                Review every view and read across your posts. Filters apply first, then each table sorts the full result set before showing the top 25 records.
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.blog.index') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400 hover:text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-gray-500 dark:hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-8.25A2.25 2.25 0 0 0 17.25 3.75H6.75A2.25 2.25 0 0 0 4.5 6v12A2.25 2.25 0 0 0 6.75 20.25h6.75" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h7.5M8.25 11.25h4.5M16.5 18.75l2.25-2.25m0 0L21 14.25m-2.25 2.25V12" />
                </svg>
                <span>Manage Posts</span>
            </a>
            <a href="{{ route('blog') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 13.5V6.75A2.25 2.25 0 0 0 15.75 4.5H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5A2.25 2.25 0 0 0 6.75 19.5H12" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l6 6m0 0-6 6m6-6H9" />
                </svg>
                <span>View Blog</span>
            </a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tracked Views</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($viewStats['total']) }}</p>
                </div>
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                Across {{ number_format($viewStats['posts']) }} posts
                @if ($viewStats['latest_at'])
                    · latest {{ $viewStats['latest_at']->diffForHumans() }}
                @endif
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique Viewers</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($viewStats['users']) }}</p>
                </div>
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20a2 2 0 1 0-4 0m4 0a2 2 0 1 1 4 0m-4 0H7m10 0v-1c0-.656-.126-1.283-.356-1.857M7 20a2 2 0 1 1-4 0m4 0a2 2 0 1 0-4 0m4 0h10M7 20v-1c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM7 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Distinct signed-in readers within the current filter scope.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tracked Reads</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($readStats['total']) }}</p>
                </div>
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h11.379a2.25 2.25 0 0 1 1.591.659l1.621 1.621A2.25 2.25 0 0 1 20.5 8.371V17.25A2.25 2.25 0 0 1 18.25 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 12 2.25 2.25 5.25-5.25" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                Across {{ number_format($readStats['posts']) }} posts
                @if ($readStats['latest_at'])
                    · latest {{ $readStats['latest_at']->diffForHumans() }}
                @endif
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique Readers</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($readStats['users']) }}</p>
                </div>
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75v6l4.5 2.25" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Readers who actively marked a post as read within the current filters.</p>
        </div>
    </div>

    <flux:card class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:heading size="lg">Filters</flux:heading>
                <flux:text class="mt-1">Use shared filters to narrow both datasets before either table applies its own sort order and top 25 limit.</flux:text>
            </div>

            @if (filled($search) || filled($postId) || filled($fromDate) || filled($toDate))
                <flux:button wire:click="resetFilters" variant="ghost" icon="x-mark">Reset filters</flux:button>
            @endif
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <flux:field>
                <flux:label>Search</flux:label>
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    placeholder="Reader name, email, title, or slug"
                />
            </flux:field>

            <flux:field>
                <flux:label>Post</flux:label>
                <flux:select wire:model.live="postId">
                    <flux:select.option value="">All posts</flux:select.option>
                    @foreach ($postOptions as $postOptionId => $postOptionTitle)
                        <flux:select.option value="{{ $postOptionId }}">{{ $postOptionTitle }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>From date</flux:label>
                <flux:input type="date" wire:model.live="fromDate" />
            </flux:field>

            <flux:field>
                <flux:label>To date</flux:label>
                <flux:input type="date" wire:model.live="toDate" />
            </flux:field>
        </div>
    </flux:card>

    <div class="space-y-6">
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-col gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-700 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <flux:heading size="lg">View Activity</flux:heading>
                    <flux:text class="mt-1">Top 25 view records after the current filters and selected table sort.</flux:text>
                </div>

                <div class="flex flex-wrap gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 dark:bg-gray-700/70">{{ number_format($viewStats['total']) }} records</span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 dark:bg-gray-700/70">{{ number_format($viewStats['users']) }} viewers</span>
                </div>
            </div>

            @if ($viewRecords->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/80 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortViewsBy('reader')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Reader</span>
                                        @if ($viewSortColumn === 'reader')
                                            @if ($viewSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortViewsBy('post')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Post</span>
                                        @if ($viewSortColumn === 'post')
                                            @if ($viewSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortViewsBy('created_at')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Viewed</span>
                                        @if ($viewSortColumn === 'created_at')
                                            @if ($viewSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($viewRecords as $viewRecord)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-6 py-4 align-top">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $viewRecord->user?->name ?? 'Guest' }}</div>
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $viewRecord->user?->email ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $viewRecord->blogPost->title }}</div>
                                        <a href="{{ route('blog.post', $viewRecord->blogPost) }}" target="_blank" class="mt-1 inline-flex text-sm text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                            Open post
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 align-top text-sm text-gray-500 dark:text-gray-400">
                                        <div>{{ $viewRecord->created_at?->format('d M Y') }}</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.18em] text-gray-400 dark:text-gray-500">{{ $viewRecord->created_at?->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">No view activity found</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Try broadening your filters or wait for more readers to browse the blog.</p>
                </div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-col gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-700 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <flux:heading size="lg">Read Activity</flux:heading>
                    <flux:text class="mt-1">Top 25 read records after the current filters and selected table sort.</flux:text>
                </div>

                <div class="flex flex-wrap gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 dark:bg-gray-700/70">{{ number_format($readStats['total']) }} records</span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 dark:bg-gray-700/70">{{ number_format($readStats['users']) }} readers</span>
                </div>
            </div>

            @if ($readRecords->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/80 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortReadsBy('reader')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Reader</span>
                                        @if ($readSortColumn === 'reader')
                                            @if ($readSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortReadsBy('post')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Post</span>
                                        @if ($readSortColumn === 'post')
                                            @if ($readSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-300">
                                    <button type="button" wire:click="sortReadsBy('created_at')" class="inline-flex items-center gap-2 transition hover:text-gray-900 dark:hover:text-white">
                                        <span>Read</span>
                                        @if ($readSortColumn === 'created_at')
                                            @if ($readSortDirection === 'asc')
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .53.22l4 4a.75.75 0 1 1-1.06 1.06L10.75 5.56V17a.75.75 0 0 1-1.5 0V5.56L6.53 8.28a.75.75 0 1 1-1.06-1.06l4-4A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.53-.22l-4-4a.75.75 0 1 1 1.06-1.06l2.72 2.72V3a.75.75 0 0 1 1.5 0v11.44l2.72-2.72a.75.75 0 1 1 1.06 1.06l-4 4A.75.75 0 0 1 10 17Z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="h-3.5 w-3.5 opacity-50" viewBox="0 0 20 20" fill="currentColor"><path d="M10.53 3.22a.75.75 0 0 0-1.06 0l-4 4a.75.75 0 1 0 1.06 1.06l2.72-2.72v8.88a.75.75 0 0 0 1.5 0V5.56l2.72 2.72a.75.75 0 1 0 1.06-1.06l-4-4Z" /><path d="M9.47 16.78a.75.75 0 0 0 1.06 0l4-4a.75.75 0 0 0-1.06-1.06l-2.72 2.72V5.56a.75.75 0 0 0-1.5 0v8.88l-2.72-2.72a.75.75 0 1 0-1.06 1.06l4 4Z" /></svg>
                                        @endif
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($readRecords as $readRecord)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-6 py-4 align-top">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $readRecord->user->name }}</div>
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $readRecord->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $readRecord->blogPost->title }}</div>
                                        <a href="{{ route('blog.post', $readRecord->blogPost) }}" target="_blank" class="mt-1 inline-flex text-sm text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                            Open post
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 align-top text-sm text-gray-500 dark:text-gray-400">
                                        <div>{{ $readRecord->created_at?->format('d M Y') }}</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.18em] text-gray-400 dark:text-gray-500">{{ $readRecord->created_at?->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">No read activity found</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Readers have not marked any posts as read inside the current filter window.</p>
                </div>
            @endif
        </section>
    </div>
</div>