<?php

use App\Http\Controllers\EmailController;
use App\Livewire\Admin\BlogPostForm;
use App\Livewire\Admin\BlogPostList;
use App\Livewire\Admin\BlogPostPreview;
use App\Livewire\Admin\ContactMessageList;
use App\Livewire\Admin\ProjectForm;
use App\Livewire\Admin\ProjectList;
use App\Livewire\Blog;
use App\Livewire\BlogPost;
use App\Livewire\Contact;
use App\Livewire\Home;
use App\Livewire\Projects;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', Home::class)->name('home');
Route::get('/blog', Blog::class)->name('blog');
Route::get('/blog/{post:slug}', BlogPost::class)->name('blog.post');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/projects', Projects::class)->name('projects');

// Email management routes
Route::get('/emails/unsubscribe', [EmailController::class, 'unsubscribe'])->name('emails.unsubscribe');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/blog', BlogPostList::class)->name('blog.index');
    Route::get('/blog/create', BlogPostForm::class)->name('blog.create');
    Route::get('/blog/{postId}/edit', BlogPostForm::class)->name('blog.edit');
    Route::get('/blog/{postId}/preview', BlogPostPreview::class)->name('blog.preview');

    Route::get('/projects', ProjectList::class)->name('projects.index');
    Route::get('/projects/create', ProjectForm::class)->name('projects.create');
    Route::get('/projects/{projectId}/edit', ProjectForm::class)->name('projects.edit');

    Route::get('/contact-messages', ContactMessageList::class)->name('contact-messages.index');
});

// Auth routes
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
