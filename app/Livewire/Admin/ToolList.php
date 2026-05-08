<?php

namespace App\Livewire\Admin;

use App\Models\Tool;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Tools')]
class ToolList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'order';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteTool(int $toolId): void
    {
        $tool = Tool::findOrFail($toolId);
        $tool->delete();
        session()->flash('message', 'Tool deleted successfully!');
    }

    public function render(): View
    {
        $query = Tool::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        switch ($this->sortBy) {
            case 'latest': $query->orderBy('created_at', 'desc');
                break;
            case 'title':  $query->orderBy('title', 'asc');
                break;
            default:       $query->orderBy('order', 'asc');
        }

        return view('livewire.admin.tool-list', ['tools' => $query->with('uploadedImage')->paginate(15)]);
    }
}
