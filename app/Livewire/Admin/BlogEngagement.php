<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Models\UserBlogRead;
use App\Models\UserBlogView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Blog Engagement')]
class BlogEngagement extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'post')]
    public string $postId = '';

    #[Url(as: 'from')]
    public string $fromDate = '';

    #[Url(as: 'to')]
    public string $toDate = '';

    public string $viewSortColumn = 'created_at';

    public string $viewSortDirection = 'desc';

    public string $readSortColumn = 'created_at';

    public string $readSortDirection = 'desc';

    public function resetFilters(): void
    {
        $this->search = '';
        $this->postId = '';
        $this->fromDate = '';
        $this->toDate = '';
    }

    public function sortViewsBy(string $column): void
    {
        if (! $this->isSortableColumn($column)) {
            return;
        }

        if ($this->viewSortColumn === $column) {
            $this->viewSortDirection = $this->viewSortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->viewSortColumn = $column;
        $this->viewSortDirection = 'asc';
    }

    public function sortReadsBy(string $column): void
    {
        if (! $this->isSortableColumn($column)) {
            return;
        }

        if ($this->readSortColumn === $column) {
            $this->readSortDirection = $this->readSortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->readSortColumn = $column;
        $this->readSortDirection = 'asc';
    }

    public function render()
    {
        $viewQuery = $this->buildActivityQuery(UserBlogView::query(), 'user_blog_views');
        $readQuery = $this->buildActivityQuery(UserBlogRead::query(), 'user_blog_reads');

        return view('livewire.admin.blog-engagement', [
            'postOptions' => BlogPost::query()->orderBy('title', 'asc')->pluck('title', 'id'),
            'viewStats' => $this->buildStats($viewQuery, 'user_blog_views'),
            'readStats' => $this->buildStats($readQuery, 'user_blog_reads'),
            'viewRecords' => $this->applyOrdering(
                clone $viewQuery,
                'user_blog_views',
                $this->viewSortColumn,
                $this->viewSortDirection,
            )->limit(25)->get(),
            'readRecords' => $this->applyOrdering(
                clone $readQuery,
                'user_blog_reads',
                $this->readSortColumn,
                $this->readSortDirection,
            )->limit(25)->get(),
        ]);
    }

    private function buildActivityQuery(Builder $query, string $table): Builder
    {
        $search = trim($this->search);

        return $query
            ->select($table.'.*')
            ->leftJoin('users', 'users.id', '=', $table.'.user_id')
            ->join('blog_posts', 'blog_posts.id', '=', $table.'.blog_post_id')
            ->with([
                'user:id,name,email',
                'blogPost:id,title,slug',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%'.$search.'%';

                $query->where(function (Builder $query) use ($term): void {
                    $query->where('users.name', 'like', $term)
                        ->orWhere('users.email', 'like', $term)
                        ->orWhere('blog_posts.title', 'like', $term)
                        ->orWhere('blog_posts.slug', 'like', $term);
                });
            })
            ->when($this->postId !== '', function (Builder $query) use ($table): void {
                $query->where($table.'.blog_post_id', (int) $this->postId);
            })
            ->when($this->fromDate !== '', function (Builder $query) use ($table): void {
                $query->whereDate($table.'.created_at', '>=', $this->fromDate);
            })
            ->when($this->toDate !== '', function (Builder $query) use ($table): void {
                $query->whereDate($table.'.created_at', '<=', $this->toDate);
            });
    }

    private function buildStats(Builder $query, string $table): array
    {
        $latestAt = (clone $query)->max($table.'.created_at');

        return [
            'total' => (clone $query)->count($table.'.id'),
            'users' => (clone $query)->distinct()->count($table.'.user_id'),
            'posts' => (clone $query)->distinct()->count($table.'.blog_post_id'),
            'latest_at' => $latestAt ? Carbon::parse($latestAt) : null,
        ];
    }

    private function applyOrdering(Builder $query, string $table, string $column, string $direction): Builder
    {
        return match ($column) {
            'post' => $query
                ->orderBy('blog_posts.title', $direction)
                ->orderBy($table.'.created_at', 'desc'),
            'reader' => $query
                ->orderBy('users.name', $direction)
                ->orderBy($table.'.created_at', 'desc'),
            default => $query->orderBy($table.'.created_at', $direction),
        };
    }

    private function isSortableColumn(string $column): bool
    {
        return in_array($column, ['created_at', 'post', 'reader'], true);
    }
}
