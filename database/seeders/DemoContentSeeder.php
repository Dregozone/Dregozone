<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Project;
use App\Models\Tool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Realistic local demo content for designing and reviewing the site.
 *
 * Safe to re-run (every record is upserted on a natural key) and refuses to
 * run in production: `php artisan db:seed --class=DemoContentSeeder`.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('DemoContentSeeder never runs in production. Skipped.');

            return;
        }

        $this->seedPosts();
        $this->seedProjects();
        $this->seedTools();
        $this->seedContactMessages();
        $this->seedSubscribers();

        $this->command?->info('Demo content seeded.');
    }

    protected function seedPosts(): void
    {
        $posts = [
            ['Running thirty coding agents without losing the plot', ['AI', 'Claude', 'Workflow'], 'How Life OS keeps a fleet of Claude Code sessions in separate worktrees, and the kanban habits that stop them colliding.', 2140, 4],
            ['A verifier model is the cheapest code review you will ever buy', ['AI', 'Laravel'], 'Chaining a second local model to check the first catches most of the nonsense before it reaches me. Here is the pipeline.', 1580, 11],
            ['SQLite in production: what the benchmarks do not tell you', ['SQL', 'Laravel', 'Performance'], 'WAL mode, busy timeouts and the queue configuration that made SQLite boring in the best possible way.', 1265, 19],
            ['Eloquent performance patterns I now use by default', ['Laravel', 'SQL', 'Performance'], 'Subquery selects, constrained eager loads and the memory tricks that keep large reports fast.', 980, 27],
            ['Motion that explains: animating interfaces with intent', ['UX', 'CSS', 'Livewire'], 'Every animation should answer a question the user is asking. A practical checklist for purposeful motion.', 845, 33],
            ['Generating hero images on an 8 GB GPU', ['AI', 'ComfyUI'], 'SDXL Turbo, a latent hires pass and tiled VAE decoding: the recipe behind the plates on this site.', 720, 41],
            ['Livewire 4 islands in a real app', ['Livewire', 'Laravel'], 'Where islands shine, where they surprise you, and how to keep server round-trips honest.', 610, 52],
            ['Training for Brighton while shipping software', ['Running', 'Productivity'], 'What marathon training has taught me about consistency, recovery and sustainable pace at work.', 455, 63],
            ['Writing technical docs people actually read', ['Writing', 'Workflow'], 'Decision records, short READMEs and the art of explaining trade-offs before anyone asks.', 390, 75],
            ['Building MCP servers for Laravel projects', ['AI', 'MCP', 'Laravel'], 'Exposing a Laravel app to agents safely: tool design, permissions and the tests that keep it honest.', 330, 88],
        ];

        foreach ($posts as [$title, $tags, $excerpt, $views, $daysAgo]) {
            BlogPost::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $this->articleBody($excerpt),
                'tags' => $tags,
                'status' => 'published',
                'published_at' => now()->subDays($daysAgo),
                'views' => $views,
            ]);
        }

        foreach (['Notes on a local-first agent memory', 'What I got wrong about queues'] as $draft) {
            BlogPost::updateOrCreate(['slug' => Str::slug($draft)], [
                'title' => $draft,
                'excerpt' => 'Work in progress.',
                'content' => '<p>Draft in progress.</p>',
                'tags' => ['Draft'],
                'status' => 'draft',
                'published_at' => null,
                'views' => 0,
            ]);
        }
    }

    protected function articleBody(string $excerpt): string
    {
        return <<<HTML
            <p>{$excerpt}</p>
            <h2>The problem</h2>
            <p>Most of the friction in day-to-day delivery is not the hard engineering. It is the repeated, slightly different work that nobody quite owns. That is where a small system pays for itself many times over.</p>
            <ul>
                <li><strong>Keep the loop short:</strong> fast feedback beats clever tooling.</li>
                <li><strong>Make state visible:</strong> if you cannot see it, you cannot trust it.</li>
                <li><strong>Automate the boring checks:</strong> humans should review decisions, not formatting.</li>
            </ul>
            <h2>What worked</h2>
            <p>The approach that stuck was the simplest one that could be tested. Here is the core of it:</p>
            <pre><code>php artisan test --compact --filter=PipelineTest</code></pre>
            <blockquote>Good tools disappear. You only notice them when they are missing.</blockquote>
            <h3>Trade-offs</h3>
            <p>Nothing here is free. Every extra moving part needs an owner, a test and a way to turn it off. The <code>--dry-run</code> flag earned its place more than once.</p>
            <ol>
                <li>Start with the manual version and write down every step.</li>
                <li>Automate the step you repeat most.</li>
                <li>Measure before automating the next one.</li>
            </ol>
            <p>If you are trying something similar, I would love to hear how it goes.</p>
            HTML;
    }

    protected function seedProjects(): void
    {
        $projects = [
            ['Life OS', 'A first-person 3D office for orchestrating a fleet of coding agents, each desk running its own session in its own git worktree.', ['Three.js', 'Laravel', 'Claude Code', 'MCP'], 'in_progress', true, '/images/now/office-floor.jpg'],
            ['Local model pipeline', 'Multi-model workflows where a verifier model checks and improves the first model\'s output before it reaches a person.', ['Ollama', 'LM Studio', 'Laravel AI SDK'], 'in_progress', false, null],
            ['Dregozone', 'This website: a Laravel 13 and Livewire 4 portfolio with a hand-built design system and a live canvas aurora.', ['Laravel', 'Livewire', 'Tailwind', 'Flux'], 'completed', true, '/images/home/hero-aurora-960.webp'],
            ['Language companion', 'A chatbot with a distinct personality for practising languages, with spaced-repetition review built in.', ['Laravel AI SDK', 'Livewire', 'SQLite'], 'completed', true, null],
            ['Reporting dashboard refresh', 'Rebuilt a slow reporting suite with constrained eager loads and indexed subqueries; key reports went from seconds to milliseconds.', ['Laravel', 'SQL', 'Performance'], 'completed', false, null],
            ['Hedgehog camera', 'A Raspberry Pi wildlife camera that captures the garden\'s night-time visitors and posts the best clips.', ['Raspberry Pi', 'Python', 'FFmpeg'], 'completed', false, null],
        ];

        foreach ($projects as $order => [$title, $description, $technologies, $status, $featured, $image]) {
            Project::updateOrCreate(['title' => $title], [
                'description' => $description,
                'technologies' => $technologies,
                'status' => $status,
                'featured' => $featured,
                'image' => $image,
                'order' => $order + 1,
            ]);
        }
    }

    protected function seedTools(): void
    {
        Tool::updateOrCreate(['title' => 'Running Pace Calculator'], [
            'description' => 'Work out the pace you need to hit a target time, in kilometres or miles.',
            'url' => '/run-tools',
            'order' => 1,
        ]);
    }

    protected function seedContactMessages(): void
    {
        $messages = [
            ['Priya Shah', 'priya@example.com', 'Senior Laravel role', 'work_request', 'new'],
            ['Tom Becker', 'tom@example.com', 'Your agent orchestration setup', 'general', 'new'],
            ['Ana Lima', 'ana@example.com', 'Speaking at our meetup', 'partnership', 'read'],
            ['Jordan Ellis', 'jordan@example.com', 'Performance audit for our app', 'work_request', 'replied'],
        ];

        foreach ($messages as $index => [$name, $email, $subject, $type, $status]) {
            ContactMessage::updateOrCreate(['email' => $email, 'subject' => $subject], [
                'name' => $name,
                'message' => 'Hi Anders, I came across your site and the AI lab write-up. Would you be open to a short call about this next week?',
                'type' => $type,
                'status' => $status,
                'created_at' => now()->subDays($index * 3 + 1),
            ]);
        }
    }

    protected function seedSubscribers(): void
    {
        foreach (['ada', 'grace', 'linus', 'margaret', 'ken', 'barbara'] as $index => $name) {
            NewsletterSubscriber::updateOrCreate(['email' => "{$name}@example.com"], [
                'name' => Str::title($name),
                'is_subscribed' => $index !== 5,
                'subscribed_at' => now()->subDays(($index + 1) * 9),
                'unsubscribed_at' => $index === 5 ? now()->subDays(2) : null,
            ]);
        }
    }
}
