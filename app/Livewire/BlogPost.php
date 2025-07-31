<?php

namespace App\Livewire;

use App\Models\BlogPost as BlogPostModel;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.blank')]
class BlogPost extends Component
{
    public BlogPostModel $post;

    public function mount(BlogPostModel $post)
    {
        $this->post = $post;
        
        // Increment view count
        $this->post->increment('views');
    }

    public function render()
    {
        $relatedPosts = BlogPostModel::published()
            ->where('id', '!=', $this->post->id)
            ->whereJsonContains('tags', $this->post->tags ?? [])
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $additionalPosts = BlogPostModel::published()
                ->where('id', '!=', $this->post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->take(3 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        return view('livewire.blog-post', [
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
