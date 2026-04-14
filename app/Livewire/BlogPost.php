<?php

namespace App\Livewire;

use App\Models\BlogPost as BlogPostModel;
use App\Models\UserBlogRead;
use App\Models\UserBlogView;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BlogPost extends Component
{
    public BlogPostModel $post;

    public bool $isRead = false;

    public ?string $readAt = null;

    public function mount(BlogPostModel $post): void
    {
        $this->post = $post->load('image');

        // Increment view count
        $this->post->increment('views');

        // Record authenticated user view
        if (auth()->check()) {
            UserBlogView::create([
                'user_id' => auth()->id(),
                'blog_post_id' => $this->post->id,
            ]);

            $read = UserBlogRead::where('user_id', auth()->id())
                ->where('blog_post_id', $this->post->id)
                ->first();

            if ($read) {
                $this->isRead = true;
                $this->readAt = $read->created_at->format('d/m/Y');
            }
        }
    }

    public function markAsRead(): void
    {
        if (! auth()->check()) {
            return;
        }

        $read = UserBlogRead::firstOrCreate([
            'user_id' => auth()->id(),
            'blog_post_id' => $this->post->id,
        ]);

        $this->isRead = true;
        $this->readAt = $read->created_at->format('d/m/Y');
    }

    public function markAsUnread(): void
    {
        if (! auth()->check()) {
            return;
        }

        UserBlogRead::where('user_id', auth()->id())
            ->where('blog_post_id', $this->post->id)
            ->delete();

        $this->isRead = false;
        $this->readAt = null;
    }

    public function render()
    {
        $relatedPosts = BlogPostModel::published()
            ->where('id', '!=', $this->post->id)
            ->where(function ($query) {
                foreach ($this->post->tags ?? [] as $tag) {
                    $query->orWhereJsonContains('tags', $tag);
                }
            })
            ->take(3)
            ->with('image')
            ->get();

        if ($relatedPosts->count() < 3) {
            $additionalPosts = BlogPostModel::published()
                ->where('id', '!=', $this->post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->take(3 - $relatedPosts->count())
                ->with('image')
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        return view('livewire.blog-post', [
            'relatedPosts' => $relatedPosts,
        ]);
    }
}

