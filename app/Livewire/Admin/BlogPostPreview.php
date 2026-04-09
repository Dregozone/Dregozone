<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Preview: {post.title}')]
class BlogPostPreview extends Component
{
    public BlogPost $post;

    public function mount(int $postId): void
    {
        $this->post = BlogPost::findOrFail($postId);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.blog-post-preview');
    }
}
