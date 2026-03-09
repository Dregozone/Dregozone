<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Projects')]
class ProjectList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $sortBy = 'order';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteProject(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $project->delete();
        session()->flash('message', 'Project deleted successfully!');
    }

    public function toggleFeatured(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $project->update(['featured' => ! $project->featured]);
        session()->flash('message', 'Project updated!');
    }

    public function render(): \Illuminate\View\View
    {
        $query = Project::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        switch ($this->sortBy) {
            case 'latest': $query->orderBy('created_at', 'desc');
                break;
            case 'title':  $query->orderBy('title', 'asc');
                break;
            default:       $query->orderBy('order', 'asc');
        }

        return view('livewire.admin.project-list', ['projects' => $query->paginate(15)]);
    }
}
