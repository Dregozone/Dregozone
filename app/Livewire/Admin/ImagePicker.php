<?php

namespace App\Livewire\Admin;

use App\Models\UploadedImage;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class ImagePicker extends Component
{
    #[Modelable]
    public ?int $selectedImageId = null;

    public string $search = '';

    public bool $open = false;

    public function updatingSearch(): void
    {
        // Reset to first page equivalent when search changes — handled in view
    }

    public function select(int $imageId): void
    {
        $this->selectedImageId = $imageId;
        $this->open = false;
        $this->search = '';
    }

    public function clear(): void
    {
        $this->selectedImageId = null;
    }

    public function render(): \Illuminate\View\View
    {
        $images = $this->open
            ? UploadedImage::library()
                ->when($this->search, fn ($q) => $q->search($this->search))
                ->latest()
                ->limit(48)
                ->get()
            : collect();

        $selected = $this->selectedImageId
            ? UploadedImage::find($this->selectedImageId)
            : null;

        return view('livewire.admin.image-picker', compact('images', 'selected'));
    }
}
