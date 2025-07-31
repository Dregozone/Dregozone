<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Models\BlogPost;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.blank')]
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
