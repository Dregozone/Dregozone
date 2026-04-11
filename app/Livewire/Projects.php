<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Projects')]
class Projects extends Component
{
    public function render()
    {
        $featuredProjects = Project::featured()->with('uploadedImage')->get();
        $completedProjects = Project::completed()->with('uploadedImage')->get();
        $inProgressProjects = Project::inProgress()->with('uploadedImage')->get();

        return view('livewire.projects', [
            'featuredProjects' => $featuredProjects,
            'completedProjects' => $completedProjects,
            'inProgressProjects' => $inProgressProjects,
        ]);
    }
}
