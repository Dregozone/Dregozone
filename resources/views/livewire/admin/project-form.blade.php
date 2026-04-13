<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $isEditing ? 'Edit Project' : 'Add New Project' }}
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                {{ $isEditing ? 'Update your project details' : 'Add a new portfolio project' }}
            </p>
        </div>
        <a href="{{ route('admin.projects.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
            ← Back to Projects
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
                placeholder="Enter project title..." required>
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
                placeholder="Describe the project..." required></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Project Image -->
        <div wire:ignore
            x-data="{
                preview: @json($currentImageBase64 ?? ''),
                uploading: false,
                uploadError: '',
                removeImage() {
                    this.preview = '';
                    this.uploadError = '';
                    $wire.set('pendingImageId', null);
                },
                async handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.uploading = true;
                    this.uploadError = '';
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const base64 = e.target.result;
                        this.preview = base64;
                        try {
                            const response = await fetch('{{ route('admin.images.upload') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                },
                                body: JSON.stringify({ base64_data: base64 }),
                            });
                            if (!response.ok) throw new Error('Upload failed');
                            const data = await response.json();
                            $wire.set('pendingImageId', data.id);
                        } catch (err) {
                            this.uploadError = 'Failed to upload image. Please try again.';
                            this.preview = '';
                        } finally {
                            this.uploading = false;
                        }
                    };
                    reader.readAsDataURL(file);
                },
            }"
        >
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Project Image
            </label>

            <template x-if="preview">
                <div class="mb-3">
                    <img :src="preview" alt="Project image" class="h-32 w-auto rounded-lg object-cover">
                    <button type="button" @click="removeImage"
                        class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">
                        Remove image
                    </button>
                </div>
            </template>

            <template x-if="!preview">
                <label for="imageFile"
                    class="flex flex-col items-center justify-center w-full px-3 py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200">
                    <input type="file" id="imageFile" accept="image/*" class="sr-only" @change="handleFileSelect">
                    <span x-show="!uploading" class="text-sm text-gray-500 dark:text-gray-400">
                        Click to select an image file
                    </span>
                    <span x-show="uploading" class="text-sm text-blue-600 dark:text-blue-400">
                        Uploading…
                    </span>
                </label>
            </template>

            <p x-show="uploadError" x-text="uploadError" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Supports JPEG, PNG, GIF, WebP and other common image formats. The image is uploaded separately before the project is saved.
            </p>
        </div>

        <!-- Technologies -->
        <flux:field>
            <flux:label>Technologies</flux:label>
            <flux:pillbox wire:model="technologies" multiple placeholder="Choose technologies...">
                @foreach ($availableTechnologies as $tech)
                    <flux:pillbox.option value="{{ $tech }}">{{ $tech }}</flux:pillbox.option>
                @endforeach
            </flux:pillbox>
            <flux:error name="technologies" />
        </flux:field>

        <!-- URLs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Live URL
                </label>
                <input type="url" wire:model="url" id="url"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="https://example.com">
                @error('url')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="github_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    GitHub URL
                </label>
                <input type="url" wire:model="github_url" id="github_url"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="https://github.com/username/repo">
                @error('github_url')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status, Order and Featured -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status *
                </label>
                <select wire:model="status" id="status"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="archived">Archived</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
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
            <div class="flex items-end pb-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" wire:model="featured" id="featured"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured project</span>
                </label>
                @error('featured')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.projects.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors duration-200">
                {{ $isEditing ? 'Update Project' : 'Create Project' }}
            </button>
        </div>
    </form>
</div>
