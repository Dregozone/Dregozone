<?php

namespace App\Livewire\Admin;

use App\Models\Tool;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ToolForm extends Component
{
    public Tool $tool;

    public string $title = '';

    public string $description = '';

    public string $url = '';

    public int $order = 0;

    public ?int $pendingImageId = null;

    public bool $isEditing = false;

    protected array $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'url' => 'required|string|max:255',
        'order' => 'integer|min:0',
    ];

    public function mount(?int $toolId = null): void
    {
        if ($toolId) {
            $this->tool = Tool::findOrFail($toolId);
            $this->isEditing = true;
            $this->title = $this->tool->title;
            $this->description = $this->tool->description;
            $this->url = $this->tool->url;
            $this->order = $this->tool->order;
            $this->pendingImageId = $this->tool->image_id;
        } else {
            $this->tool = new Tool;
        }
    }

    public function save(): void
    {
        $this->validate();

        $this->tool->title = $this->title;
        $this->tool->description = $this->description;
        $this->tool->url = $this->url;
        $this->tool->order = $this->order;
        $this->tool->image_id = $this->pendingImageId;
        $this->tool->save();

        session()->flash('message', $this->isEditing ? 'Tool updated successfully!' : 'Tool created successfully!');

        $this->redirect(route('admin.tools.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.tool-form');
    }
}
