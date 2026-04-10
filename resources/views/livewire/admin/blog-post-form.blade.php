    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $isEditing ? 'Edit Post' : 'Create New Post' }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    {{ $isEditing ? 'Update your blog post' : 'Write a new blog post' }}
                </p>
            </div>
            <a href="{{ route('admin.blog.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                ← Back to Posts
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
                    placeholder="Enter post title..." required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Excerpt *
                </label>
                <textarea wire:model="excerpt" id="excerpt" rows="3"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="Brief description of the post..." required></textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Content *
                </label>
                <textarea wire:model="content" id="content" rows="15"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono"
                    placeholder="Write your blog post content here. You can use HTML tags for formatting..." required></textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    You can use HTML tags for formatting. Common tags: &lt;h2&gt;, &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;,
                    &lt;ul&gt;, &lt;li&gt;, &lt;code&gt;
                </p>
            </div>

            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Featured Image
                </label>
                <input type="file" wire:model="featured_image" id="featured_image" accept="image/*"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <flux:field>
                <flux:label>Tags</flux:label>
                <flux:pillbox wire:model="tags" multiple searchable placeholder="Choose tags...">
                    @foreach ($availableTags as $tag)
                        <flux:pillbox.option value="{{ $tag }}">{{ $tag }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
                <flux:error name="tags" />
            </flux:field>

            <!-- Create New Tag -->
            <div class="rounded-md border border-dashed border-gray-300 dark:border-gray-600 p-4">
                @if ($showNewTagInput)
                    <div class="flex items-center gap-3">
                        <input type="text" wire:model="newTagName"
                            placeholder="New tag name..."
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                            wire:keydown.enter.prevent="createTag" />
                        <button type="button" wire:click="createTag"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md font-medium transition-colors duration-200">
                            Add Tag
                        </button>
                        <button type="button" wire:click="$set('showNewTagInput', false)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                    @error('newTagName')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <button type="button" wire:click="$set('showNewTagInput', true)"
                        class="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create a new tag
                    </button>
                @endif
            </div>

            <!-- Status and Publish Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status *
                    </label>
                    <select wire:model="status" id="status"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        required>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Publish Date
                    </label>
                    <input type="datetime-local" wire:model="published_at" id="published_at"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.blog.index') }}"
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors duration-200">
                    {{ $isEditing ? 'Update Post' : 'Create Post' }}
                </button>
            </div>
        </form>
    </div>



