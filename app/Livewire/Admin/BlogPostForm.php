<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class BlogPostForm extends Component
{
    use WithFileUploads;

    public BlogPost $post;

    public $title = '';

    public $excerpt = '';

    public $content = '';

    public $tags = [];

    public $status = 'draft';

    public $featured_image;

    public $published_at;

    public $isEditing = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'excerpt' => 'required|min:10|max:500',
        'content' => 'required|min:50',
        'tags' => 'array',
        'status' => 'required|in:draft,published',
        'featured_image' => 'nullable|image|max:2048',
        'published_at' => 'nullable|date',
    ];

    public function mount($postId = null)
    {
        if ($postId) {
            $this->post = BlogPost::findOrFail($postId);
            $this->isEditing = true;
            $this->title = $this->post->title;
            $this->excerpt = $this->post->excerpt;
            $this->content = $this->post->content;
            $this->tags = $this->post->tags ?? [];
            $this->status = $this->post->status;
            $this->published_at = $this->post->published_at?->format('Y-m-d\TH:i');
        } else {
            $this->post = new BlogPost;
        }
    }

    public function save()
    {
        $this->validate();

        $this->post->title = $this->title;
        $this->post->slug = Str::slug($this->title);
        $this->post->excerpt = $this->excerpt;
        $this->post->content = $this->content;
        $this->post->tags = $this->tags;
        $this->post->status = $this->status;
        $this->post->published_at = $this->status === 'published' ? ($this->published_at ?? now()) : null;

        if ($this->featured_image) {
            $this->post->featured_image = $this->featured_image->store('blog-images', 'public');
        }

        $this->post->save();

        session()->flash('message', $this->isEditing ? 'Post updated successfully!' : 'Post created successfully!');

        return redirect()->route('admin.blog.index');
    }

    public function addTag()
    {
        $tag = trim(request('tag'));
        if ($tag && ! in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function render()
    {
        return view('livewire.admin.blog-post-form');
    }
}
