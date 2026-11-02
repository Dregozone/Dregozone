<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Developer & Writer')]
class Home extends Component
{
    /**
     * Redesign direction to preview (temporary, used to compare design directions).
     */
    #[Url]
    public string $design = '';

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

        $view = match ($this->design) {
            'nordic' => 'livewire.home.nordic',
            'aurora' => 'livewire.home.aurora',
            default => 'livewire.home',
        };

        return view($view, [
            'featuredProjects' => $featuredProjects,
            'recentBlogPosts' => $recentBlogPosts,
            'completedProjects' => $completedProjects,
            'inProgressProjects' => $inProgressProjects,
            'topTags' => $topTags,
            'tools' => $tools,
            'capabilities' => $this->capabilities(),
            'aiPipeline' => $this->aiPipeline(),
            'heroRecipe' => $this->heroRecipe(),
            'lifeOsStats' => $this->lifeOsStats(),
            'lifeOsImages' => $this->lifeOsImages(),
        ]);
    }

    /**
     * What I bring to a team, framed as outcomes rather than a skills list.
     *
     * @return list<array{title: string, description: string, stack: list<string>}>
     */
    protected function capabilities(): array
    {
        return [
            [
                'title' => 'Product engineering',
                'description' => 'Laravel and Livewire applications shaped around the domain, from the data model and queues to the last easing curve.',
                'stack' => ['Laravel', 'Livewire', 'PHP 8.4', 'SQL'],
            ],
            [
                'title' => 'Interfaces & experience',
                'description' => 'Accessible, responsive interfaces where motion explains rather than decorates, and nothing makes a visitor wait.',
                'stack' => ['Tailwind', 'Alpine', 'Flux', 'UX research'],
            ],
            [
                'title' => 'AI-assisted engineering',
                'description' => 'Local models, verification pipelines and agent fleets that make a small team ship like a large one, with the checks to trust it.',
                'stack' => ['Laravel AI SDK', 'Ollama', 'Claude', 'MCP'],
            ],
            [
                'title' => 'Delivery & care',
                'description' => 'Tests, CI, observability and clear writing: the habits that keep a codebase pleasant to work in five years later.',
                'stack' => ['Pest', 'CI/CD', 'Nightwatch', 'Docs'],
            ],
        ];
    }

    /**
     * The multi-level local AI workflow, in the order work flows through it.
     *
     * @return list<array{key: string, label: string, title: string, description: string, tools: list<string>}>
     */
    protected function aiPipeline(): array
    {
        return [
            [
                'key' => 'draft',
                'label' => '01 · Draft',
                'title' => 'Local models, zero per-token cost',
                'description' => 'Hand-picked models run on my own GPU through Ollama and LM Studio for routine generation and experiments.',
                'tools' => ['Ollama', 'LM Studio', 'RTX 2060 Super'],
            ],
            [
                'key' => 'verify',
                'label' => '02 · Verify',
                'title' => 'A second model checks the first',
                'description' => 'Outputs feed a verifier model for optimisation and checking, so results are tested before they ever reach me.',
                'tools' => ['Multi-model pipelines', 'Structured output'],
            ],
            [
                'key' => 'integrate',
                'label' => '03 · Integrate',
                'title' => 'Built into real Laravel apps',
                'description' => 'Chatbots with distinct personalities, language-learning and coding assistants, built with the Laravel AI SDK.',
                'tools' => ['Laravel AI SDK', 'Livewire', 'Queues'],
            ],
            [
                'key' => 'orchestrate',
                'label' => '04 · Orchestrate',
                'title' => 'A fleet of agents, each in its own worktree',
                'description' => 'Claude Code agents with custom skills and MCP servers, orchestrated through Life OS so 30 sessions never collide.',
                'tools' => ['Claude Code', 'Skills', 'MCP', 'git worktrees'],
            ],
        ];
    }

    /**
     * The local ComfyUI recipe used to generate the hero plates on this page.
     *
     * @return list<array{node: string, detail: string}>
     */
    protected function heroRecipe(): array
    {
        return [
            ['node' => 'Checkpoint', 'detail' => 'SDXL Turbo fp16'],
            ['node' => 'KSampler', 'detail' => '1024×576 · 4 steps · cfg 1.0'],
            ['node' => 'Latent upscale', 'detail' => '1536×864 · bislerp'],
            ['node' => 'KSampler', 'detail' => '5 steps · denoise 0.45'],
            ['node' => 'VAE decode', 'detail' => 'tiled · fits in 8 GB'],
        ];
    }

    /**
     * @return list<array{value: string, label: string, countable: bool}>
     */
    protected function lifeOsStats(): array
    {
        return [
            ['value' => '30', 'label' => 'agents running at once', 'countable' => true],
            ['value' => '12', 'label' => 'projects, each in its own worktree', 'countable' => true],
            ['value' => 'Live', 'label' => 'kanban boards tracking every task', 'countable' => false],
        ];
    }

    /**
     * @return list<array{file: string, caption: string}>
     */
    protected function lifeOsImages(): array
    {
        return [
            ['file' => 'office-floor.jpg', 'caption' => 'The office floor: each pod of desks works on one project.'],
            ['file' => 'claude-desk-session.jpg', 'caption' => 'A Claude Code session running at a desk.'],
            ['file' => 'mission-control.jpg', 'caption' => 'Mission control shows every pod and what needs me.'],
            ['file' => 'desk-roles.jpg', 'caption' => 'Each desk takes a job: Claude Code, OpenCode, a runner or a browser.'],
        ];
    }
}
