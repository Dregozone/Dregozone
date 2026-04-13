<div>
    @if ($selected)
        <div class="flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
            <img src="{{ $selected->base64_data }}" alt="{{ $selected->name }}"
                class="h-16 w-16 rounded object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $selected->name }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">ID #{{ $selected->id }}</p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <flux:button wire:click="$set('open', true)" variant="ghost" size="sm">Change</flux:button>
                <flux:button wire:click="clear" variant="ghost" size="sm" icon="x-mark" />
            </div>
        </div>
    @else
        <flux:button wire:click="$set('open', true)" variant="outline" icon="photo" class="w-full justify-center py-6">
            Choose image from library
        </flux:button>
    @endif

    @if ($open)
        <div class="mt-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg">
            <div class="flex items-center gap-3 p-3 border-b border-zinc-200 dark:border-zinc-700">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search images…" icon="magnifying-glass" class="flex-1" />
                <flux:button wire:click="$set('open', false)" variant="ghost" icon="x-mark" />
            </div>

            <div class="p-3 max-h-80 overflow-y-auto">
                @if ($images->isEmpty())
                    <p class="text-center py-8 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $search ? 'No images match your search.' : 'No images in library yet.' }}
                        @if (! $search)
                            <a href="{{ route('admin.images.index') }}" class="underline ml-1" target="_blank">Upload images</a>
                        @endif
                    </p>
                @else
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                        @foreach ($images as $image)
                            <button
                                type="button"
                                wire:click="select({{ $image->id }})"
                                title="{{ $image->name }}"
                                class="group relative aspect-square rounded overflow-hidden border-2 transition-colors
                                    {{ $selectedImageId === $image->id
                                        ? 'border-blue-500'
                                        : 'border-transparent hover:border-zinc-400 dark:hover:border-zinc-500' }}"
                            >
                                <img src="{{ $image->base64_data }}" alt="{{ $image->name }}"
                                    class="h-full w-full object-cover">
                                @if ($selectedImageId === $image->id)
                                    <div class="absolute inset-0 bg-blue-500/20 flex items-center justify-center">
                                        <flux:icon.check-circle class="h-5 w-5 text-blue-600" />
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
