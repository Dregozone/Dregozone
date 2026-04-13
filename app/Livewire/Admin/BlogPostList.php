<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Blog Posts')]
class BlogPostList extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public $sortBy = 'latest';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deletePost($postId)
    {
        $post = BlogPost::findOrFail($postId);
        $post->delete();

        session()->flash('message', 'Post deleted successfully!');
    }

    public function toggleStatus($postId)
    {
        $post = BlogPost::findOrFail($postId);
        $post->update([
            'status' => $post->status === 'published' ? 'draft' : 'published',
            'published_at' => $post->status === 'draft' ? now() : null,
        ]);

        session()->flash('message', 'Post status updated!');
    }

    public function render()
    {
        $query = BlogPost::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $posts = $query->with('image')->paginate(15);

        return view('livewire.admin.blog-post-list', [
            'posts' => $posts,
        ]);
    }
}
