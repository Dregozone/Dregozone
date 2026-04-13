<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\UploadedImage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ProjectForm extends Component
{
    public Project $project;

    public string $title = '';

    public string $description = '';

    public array $technologies = [];

    public string $status = 'in_progress';

    public string $url = '';

    public string $github_url = '';

    public int $order = 0;

    public bool $featured = false;

    public ?int $pendingImageId = null;

    public bool $isEditing = false;

    public array $availableTechnologies = [
        'Alpine.js', 'Bootstrap', 'FFmpeg', 'JavaScript', 'jQuery',
        'Laravel', 'Livewire', 'MySQL', 'PHP', 'PostgreSQL',
        'Redis', 'Swagger', 'Tailwind CSS', 'TypeScript', 'Vue.js',
        'WebSockets',
    ];

    protected array $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'technologies' => 'array',
        'status' => 'required|in:in_progress,completed,archived',
        'url' => 'nullable|url|max:255',
        'github_url' => 'nullable|url|max:255',
        'order' => 'integer|min:0',
        'featured' => 'boolean',
    ];

    public function mount(?int $projectId = null): void
    {
        if ($projectId) {
            $this->project = Project::with('uploadedImage')->findOrFail($projectId);
            $this->isEditing = true;
            $this->title = $this->project->title;
            $this->description = $this->project->description;
            $this->technologies = $this->project->technologies ?? [];
            $this->status = $this->project->status;
            $this->url = $this->project->url ?? '';
            $this->github_url = $this->project->github_url ?? '';
            $this->order = $this->project->order;
            $this->featured = $this->project->featured;
            $this->pendingImageId = $this->project->uploadedImage?->id;
        } else {
            $this->project = new Project;
        }
    }

    public function save(): void
    {
        $this->validate();

        $this->project->title = $this->title;
        $this->project->description = $this->description;
        $this->project->technologies = $this->technologies;
        $this->project->status = $this->status;
        $this->project->url = $this->url ?: null;
        $this->project->github_url = $this->github_url ?: null;
        $this->project->order = $this->order;
        $this->project->featured = $this->featured;

        $this->project->save();

        // Sync the selected library image to this project via the morph relationship.
        $existingImage = $this->isEditing ? $this->project->uploadedImage()->first() : null;

        if ($this->pendingImageId !== null) {
            if (! $existingImage || $existingImage->id !== $this->pendingImageId) {
                // Release previous link back to library.
                $existingImage?->releaseToLibrary();

                UploadedImage::where('id', $this->pendingImageId)->update([
                    'imageable_id' => $this->project->id,
                    'imageable_type' => Project::class,
                ]);
            }
        } elseif ($existingImage) {
            $existingImage->releaseToLibrary();
        }

        session()->flash('message', $this->isEditing ? 'Project updated successfully!' : 'Project created successfully!');

        $this->redirect(route('admin.projects.index'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.project-form');
    }
}
