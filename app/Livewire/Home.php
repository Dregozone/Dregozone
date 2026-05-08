<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Developer & Writer')]
class Home extends Component
{
    public function render()
    {
        $featuredProjects = Project::featured()->with('uploadedImage')->take(3)->get();
        $completedProjects = Project::completed()->with('uploadedImage')->take(6)->get();
        $inProgressProjects = Project::inProgress()->take(3)->get();
        $topTags = Tag::topByUsage(10);
        $tools = Tool::ordered()->with('uploadedImage')->take(3)->get();

        $blogPostQuery = BlogPost::featured()->with('image');

        if (auth()->check()) {
            $blogPostQuery->with(['reads' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);
        }

        $recentBlogPosts = $blogPostQuery->take(3)->get();

        return view('livewire.home', [
            'featuredProjects' => $featuredProjects,
            'recentBlogPosts' => $recentBlogPosts,
            'completedProjects' => $completedProjects,
            'inProgressProjects' => $inProgressProjects,
            'topTags' => $topTags,
            'tools' => $tools,
        ]);
    }
}
