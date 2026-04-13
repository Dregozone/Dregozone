<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.main')]
#[Title('Blog')]
class Blog extends Component
{
    use WithPagination;

    public $search = '';

    public $tag = '';

    public $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'tag' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTag()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::published();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$this->search.'%')
                    ->orWhere('content', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->tag) {
            $query->whereJsonContains('tags', $this->tag);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        $posts = $query->with('image')->paginate(9);

        $tags = BlogPost::published()
            ->whereNotNull('tags')
            ->get()
            ->flatMap(fn ($post) => $post->tags ?? [])
            ->unique()
            ->sort()
            ->values();

        return view('livewire.blog', [
            'posts' => $posts,
            'tags' => $tags,
        ]);
    }
}
