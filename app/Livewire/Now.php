<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Now / Brag')]
class Now extends Component
{
    public function render(): View
    {
        $currentProjects = Project::inProgress();
        $recentlyCompletedProjects = Project::completed();

        return view('livewire.now', [
            'currentProjects' => (clone $currentProjects)
                ->take(3)
                ->get(['id', 'title', 'description', 'technologies', 'url']),
            'currentProjectsCount' => (clone $currentProjects)->count(),
            'recentlyCompletedProjects' => (clone $recentlyCompletedProjects)
                ->take(4)
                ->get(['id', 'title', 'description', 'technologies', 'url']),
            'recentlyCompletedProjectsCount' => (clone $recentlyCompletedProjects)->count(),
            'publishedPostsCount' => BlogPost::published()->count(),
            'totalBlogViews' => BlogPost::published()->sum('views'),
            'courseEntries' => [
                [
                    'course' => 'Intermediate to Advanced SQL',
                    'provider' => 'London Academy of IT',
                    'status' => 'Recently completed',
                    'notes' => 'Good proof of continuing depth in reporting, querying, and data thinking.',
                ],
                [
                    'course' => 'Recent Laracasts courses',
                    'provider' => 'Laracasts',
                    'status' => 'Recently completed',
                    'notes' => 'Add the exact series titles and completion dates to strengthen this section.',
                ],
                [
                    'course' => 'Additional extracurricular training',
                    'provider' => 'To add',
                    'status' => 'In progress',
                    'notes' => 'Use this row for the next course, certification, or workshop you want visible to hiring managers.',
                ],
            ],
            'strengths' => [
                [
                    'title' => 'Laravel-focused delivery',
                    'description' => 'Comfortable turning product ideas into polished web experiences with Laravel, Livewire, and pragmatic architecture.',
                ],
                [
                    'title' => 'Communication through writing',
                    'description' => 'Technical writing helps me explain trade-offs clearly, document decisions, and make knowledge easier to share across a team.',
                ],
                [
                    'title' => 'Consistent self-driven learning',
                    'description' => 'I regularly invest in courses and structured practice so I can keep improving beyond day-to-day delivery work.',
                ],
            ],
            'fillInPrompts' => [
                'Exact Laracasts course titles, dates, and any standout takeaways you want highlighted.',
                'A shortlist of measurable achievements: launches, revenue impact, performance wins, or projects you are especially proud of.',
                'Any current role, freelance niche, or industries you want hiring managers to associate with you immediately.',
                'Optional extras such as speaking, open source, mentoring, or awards if you want the page to function more like a living CV.',
            ],
        ]);
    }
}
