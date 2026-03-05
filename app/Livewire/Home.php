<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Developer & Writer')]
class Home extends Component
{
    public function render()
    {
        $featuredProjects = Project::featured()->take(3)->get();
        $recentBlogPosts = BlogPost::featured()->take(3)->get();
        $completedProjects = Project::completed()->take(6)->get();
        $inProgressProjects = Project::inProgress()->take(3)->get();

        return view('livewire.home', [
            'featuredProjects' => $featuredProjects,
            'recentBlogPosts' => $recentBlogPosts,
            'completedProjects' => $completedProjects,
            'inProgressProjects' => $inProgressProjects,
        ]);
    }
}
