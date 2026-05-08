<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $isEditing ? 'Edit Tool' : 'Add New Tool' }}
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                {{ $isEditing ? 'Update your tool details' : 'Add a new tool or utility' }}
            </p>
        </div>
        <a href="{{ route('admin.tools.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
            ← Back to Tools
        </a>
    </div>

    <form wire:submit="save" class="space-y-8">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Title *
            </label>
            <input type="text" wire:model="title" id="title"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="Enter tool title..." required>
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Description *
            </label>
            <textarea wire:model="description" id="description" rows="4"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="Describe the tool..." required></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tool Image -->
        <flux:field>
            <flux:label>Tool Image</flux:label>
            <livewire:admin.image-picker wire:model="pendingImageId" :key="'tool-image-'.$tool->id" />
            <flux:description>
                Select from your <a href="{{ route('admin.images.index') }}" target="_blank" class="underline">image library</a>. Upload new images there first.
            </flux:description>
        </flux:field>

        <!-- URL -->
        <div>
            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tool URL *
            </label>
            <input type="text" wire:model="url" id="url"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="/run-tools or https://example.com/tool">
            @error('url')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Order -->
        <div>
            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Display Order
            </label>
            <input type="number" wire:model="order" id="order" min="0"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            @error('order')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.tools.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors duration-200">
                {{ $isEditing ? 'Update Tool' : 'Create Tool' }}
            </button>
        </div>
    </form>
</div>
