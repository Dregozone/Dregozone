<?php

namespace App\Livewire\Admin;

use App\Models\UploadedImage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Image Library')]
class ImageList extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';

    public $photo;

    public string $name = '';

    public bool $showUploadForm = false;

    protected array $rules = [
        'name' => 'required|string|max:255',
        'photo' => 'required|image|max:2048',
    ];

    protected array $messages = [
        'photo.max' => 'The image must be under 2 MB. Please compress it first (e.g. using tinypng.com or squoosh.app).',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleUploadForm(): void
    {
        $this->showUploadForm = ! $this->showUploadForm;
        $this->reset(['name', 'photo']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        $base64 = 'data:'.$this->photo->getMimeType().';base64,'.base64_encode(
            file_get_contents($this->photo->getRealPath())
        );

        UploadedImage::create([
            'name' => $this->name,
            'imageable_type' => 'library',
            'imageable_id' => 0,
            'base64_data' => $base64,
        ]);

        $this->reset(['name', 'photo']);
        $this->showUploadForm = false;
        $this->resetPage();

        session()->flash('message', 'Image uploaded successfully.');
    }

    public function delete(int $imageId): void
    {
        UploadedImage::where('id', $imageId)
            ->where('imageable_type', 'library')
            ->firstOrFail()
            ->delete();

        session()->flash('message', 'Image deleted.');
    }

    public function render(): \Illuminate\View\View
    {
        $images = UploadedImage::library()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->latest()
            ->paginate(24);

        return view('livewire.admin.image-list', ['images' => $images]);
    }
}
