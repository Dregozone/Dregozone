<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Newsletter Subscribers
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                Manage and review all newsletter subscribers
            </p>
        </div>
        <a href="{{ route('admin.newsletter-subscribers.export') }}"
            target="_blank"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Active Emails (JSON)
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Active</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalActive) }}</p>
            <p class="mt-1 text-xs text-green-600 dark:text-green-400">Currently subscribed</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">New (Last 3 Months)</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($newSubscribers) }}</p>
            <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">Active subscribers joined</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Rate</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $activePercentage }}%</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Active vs all subscribers</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unsubscribed (Last 3 Months)</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($recentUnsubscribed) }}</p>
            <p class="mt-1 text-xs text-red-600 dark:text-red-400">Recent opt-outs</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search by Email</label>
                <input type="text" wire:model.live="search" placeholder="email@example.com..."
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="status"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">All</option>
                    <option value="subscribed">Subscribed</option>
                    <option value="unsubscribed">Unsubscribed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if ($subscribers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Email / Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subscribed At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Unsubscribed At
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                        {{ $subscriber->email }}
                                    </div>
                                    @if ($subscriber->name)
                                        <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">
                                            {{ $subscriber->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($subscriber->is_subscribed)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Subscribed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Unsubscribed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($subscriber->subscribed_at)
                                        {{ $subscriber->subscribed_at->format('d M Y') }}
                                        <div class="text-xs mt-0.5">{{ $subscriber->subscribed_at->format('H:i') }}</div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($subscriber->unsubscribed_at)
                                        {{ $subscriber->unsubscribed_at->format('d M Y') }}
                                        <div class="text-xs mt-0.5">{{ $subscriber->unsubscribed_at->format('H:i') }}</div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $subscribers->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">No subscribers found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($search || $status)
                        No subscribers match your current filters.
                    @else
                        No newsletter subscribers yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
