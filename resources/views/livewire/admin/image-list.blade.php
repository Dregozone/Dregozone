<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <flux:heading size="xl">Image Library</flux:heading>
            <flux:text class="mt-1">Upload and manage images for blog posts and projects.</flux:text>
        </div>
        <flux:button wire:click="toggleUploadForm" icon="{{ $showUploadForm ? 'x-mark' : 'plus' }}" variant="{{ $showUploadForm ? 'ghost' : 'primary' }}">
            {{ $showUploadForm ? 'Cancel' : 'Upload Image' }}
        </flux:button>
    </div>

    @if (session('message'))
        <flux:callout variant="success" icon="check-circle" class="mb-6">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if ($showUploadForm)
        <flux:card class="mb-8">
            <form wire:submit="save" class="space-y-4">
                <flux:heading size="lg">Upload New Image</flux:heading>
                <flux:field>
                    <flux:label>Image Name *</flux:label>
                    <flux:input wire:model="name" placeholder="e.g. Hero banner for Laravel post" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label>Image File *</flux:label>
                    <flux:input type="file" wire:model="photo" accept="image/*" />
                    <flux:description>JPEG, PNG, GIF, WebP — max 2 MB. If your image is too large, compress it first at <a href="https://tinypng.com" target="_blank" class="underline">tinypng.com</a> or <a href="https://squoosh.app" target="_blank" class="underline">squoosh.app</a>.</flux:description>
                    <flux:error name="photo" />
                </flux:field>
                <div wire:loading wire:target="photo" class="text-sm text-zinc-500">Reading file…</div>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                    Save to Library
                </flux:button>
            </form>
        </flux:card>
    @endif

    <flux:field class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by name…" icon="magnifying-glass" />
    </flux:field>

    @if ($images->isEmpty())
        <div class="text-center py-16 text-zinc-500 dark:text-zinc-400">
            <flux:icon.photo class="mx-auto h-12 w-12 mb-4 opacity-40" />
            <p>{{ $search ? 'No images match your search.' : 'No images uploaded yet.' }}</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($images as $image)
                <div class="group relative">
                    <div class="aspect-square overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800">
                        <img src="{{ $image->base64_data }}" alt="{{ $image->name }}"
                            class="h-full w-full object-cover">
                    </div>
                    <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400 truncate" title="{{ $image->name }}">
                        {{ $image->name }}
                    </p>
                    <flux:button
                        wire:click="delete({{ $image->id }})"
                        wire:confirm="Delete '{{ addslashes($image->name) }}'? This will remove it from any posts or projects using it."
                        variant="danger"
                        size="xs"
                        icon="trash"
                        class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity"
                    />
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $images->links() }}
        </div>
    @endif
</div>
