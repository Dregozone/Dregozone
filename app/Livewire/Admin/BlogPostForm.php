<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Models\Tag;
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

    public array $availableTags = [];

    public string $newTagName = '';

    public bool $showNewTagInput = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'excerpt' => 'required|min:10|max:500',
        'content' => 'required|min:50',
        'tags' => 'array',
        'status' => 'required|in:draft,published',
        'featured_image' => 'nullable|image|max:2048',
        'published_at' => 'nullable|date',
        'newTagName' => 'nullable|string|max:100',
    ];

    public function mount($postId = null)
    {
        $this->availableTags = Tag::allNames()->toArray();

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

    public function createTag(): void
    {
        $this->validateOnly('newTagName', [
            'newTagName' => 'required|string|min:2|max:100',
        ]);

        $name = trim($this->newTagName);

        $tag = Tag::createFromName($name);

        if (! in_array($tag->name, $this->availableTags)) {
            $this->availableTags[] = $tag->name;
            sort($this->availableTags);
        }

        if (! in_array($tag->name, $this->tags)) {
            $this->tags[] = $tag->name;
        }

        $this->newTagName = '';
        $this->showNewTagInput = false;
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

    public function render()
    {
        return view('livewire.admin.blog-post-form');
    }
}
