<?php

namespace App\Livewire;

use App\Models\BlogPost as BlogPostModel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BlogPost extends Component
{
    public BlogPostModel $post;

    public function mount(BlogPostModel $post)
    {
        $this->post = $post->load('uploadedImage');

        // Increment view count
        $this->post->increment('views');
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
            ->with('uploadedImage')
            ->get();

        if ($relatedPosts->count() < 3) {
            $additionalPosts = BlogPostModel::published()
                ->where('id', '!=', $this->post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->take(3 - $relatedPosts->count())
                ->with('uploadedImage')
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        return view('livewire.blog-post', [
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
