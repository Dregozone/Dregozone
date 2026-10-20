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
            'aiHighlights' => [
                [
                    'title' => 'Local models on my own GPU',
                    'description' => 'Hand-picked models run locally through Ollama and LM Studio, so routine development and experimentation cost nothing per token.',
                ],
                [
                    'title' => 'AI built into Laravel apps',
                    'description' => 'Using the Laravel AI SDK to build chatbots with distinct personalities, language-learning assistants, coding assistants, and personal development and assistant apps.',
                ],
                [
                    'title' => 'Multi-level AI workflows',
                    'description' => 'Pipelines where one model\'s output feeds another for optimisation and verification, so results are checked before they reach me.',
                ],
                [
                    'title' => 'Claude, skills and MCP',
                    'description' => 'Staying close to the latest AI providers, with a focus on Claude models: custom skills, MCP servers, and complex agent workflows.',
                ],
            ],
            'lifeOsStats' => [
                ['value' => '30', 'label' => 'agents running at once'],
                ['value' => '12', 'label' => 'projects, each in its own worktree'],
                ['value' => 'Live', 'label' => 'kanban boards tracking every task'],
            ],
            'lifeOsImages' => [
                [
                    'file' => 'office-floor.jpg',
                    'caption' => 'The office floor: each pod of desks works on one project.',
                ],
                [
                    'file' => 'mission-control.jpg',
                    'caption' => 'Mission control shows every pod and what needs me.',
                ],
                [
                    'file' => 'claude-desk-session.jpg',
                    'caption' => 'A Claude Code session running at a desk.',
                ],
                [
                    'file' => 'desk-roles.jpg',
                    'caption' => 'Each desk takes a job: Claude Code, OpenCode, a runner or a browser.',
                ],
                [
                    'file' => 'home-desktop.jpg',
                    'caption' => 'At home, the two-monitor desk runs live desktop apps.',
                ],
            ],
            'courseEntries' => [
                [
                    'course' => 'Eloquent Performance Patterns',
                    'provider' => 'Laracasts',
                    'status' => '70% complete',
                    'notes' => 'Writing faster Eloquent queries and keeping memory use down on large datasets.',
                ],
                [
                    'course' => 'High Performance SQLite',
                    'provider' => 'Aaron Francis',
                    'status' => '70% complete',
                    'notes' => 'Getting the most out of SQLite in production Laravel apps.',
                ],
                [
                    'course' => 'Livewire Screencasts',
                    'provider' => 'Livewire',
                    'status' => '70% complete',
                    'notes' => 'Caleb Porzio\'s own walkthroughs of building with Livewire.',
                ],
                [
                    'course' => 'SQL Indexes & Execution Plans',
                    'provider' => 'Udemy',
                    'status' => '30% complete',
                    'notes' => 'Reading execution plans and designing indexes that actually get used.',
                ],
                [
                    'course' => 'Laravel AI SDK',
                    'provider' => 'Laravel Daily',
                    'status' => '20% complete',
                    'notes' => 'The SDK behind my local-model chatbots and assistants.',
                ],
                [
                    'course' => 'Google UX Design',
                    'provider' => 'Coursera',
                    'status' => 'In progress',
                    'notes' => 'Structured grounding in user research, wireframing and usability testing.',
                ],
                [
                    'course' => 'Intermediate to Advanced SQL',
                    'provider' => 'London Academy of IT',
                    'status' => 'Recently completed',
                    'notes' => 'Continuing depth in reporting, querying, and data thinking.',
                ],
            ],
            'readingList' => [
                [
                    'title' => 'Engineering Management for the Rest of Us',
                    'author' => 'Sarah Drasner',
                    'reason' => 'Leading software development teams well.',
                ],
                [
                    'title' => 'Getting Real',
                    'author' => '37signals',
                    'reason' => 'Building useful products that people actually want.',
                ],
                [
                    'title' => 'The Burning Edge',
                    'author' => 'Arthur Chichester',
                    'reason' => 'Travel and culture, for something away from the screen.',
                ],
            ],
            'sideProjects' => [
                [
                    'title' => 'Raspberry Pi',
                    'description' => 'Small single-board builds for home tinkering and automation.',
                ],
                [
                    'title' => 'Rust',
                    'description' => 'Learning Rust for lower-level, performance-minded work.',
                ],
                [
                    'title' => 'Discovery board',
                    'description' => 'Microcontroller experiments, closer to the hardware.',
                ],
                [
                    'title' => 'Emma bot',
                    'description' => 'A robotics project.',
                ],
                [
                    'title' => 'Hedgehog camera',
                    'description' => 'A wildlife camera for the garden\'s night-time visitors.',
                ],
            ],
            'goals' => [
                [
                    'label' => 'April 2027',
                    'title' => 'Run the Brighton Marathon',
                    'description' => 'Booked, and training towards it.',
                ],
                [
                    'label' => 'Career',
                    'title' => 'Lead a larger development team',
                    'description' => 'Helping a team do its best work, with clear direction and room to grow.',
                ],
                [
                    'label' => 'Focus',
                    'title' => 'Optimise workflows for efficiency and output',
                    'description' => 'Automation and systems thinking, drawing on Agile, Lean and Six Sigma, to remove friction and help teams ship more.',
                ],
            ],
            'strengths' => [
                [
                    'title' => 'Laravel-focused delivery',
                    'description' => 'Comfortable turning product ideas into polished web experiences with Laravel, Livewire, and pragmatic architecture.',
                ],
                [
                    'title' => 'AI-assisted engineering',
                    'description' => 'Designing agent workflows, integrating models into real apps, and knowing where AI helps and where it needs checking.',
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
        ]);
    }
}
