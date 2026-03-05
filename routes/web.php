<?php

use App\Livewire\Admin\BlogPostForm;
use App\Livewire\Admin\BlogPostList;
use App\Livewire\Blog;
use App\Livewire\BlogPost;
use App\Livewire\Contact;
use App\Livewire\Home;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', Home::class)->name('home');
Route::get('/blog', Blog::class)->name('blog');
Route::get('/blog/{post:slug}', BlogPost::class)->name('blog.post');
Route::get('/contact', Contact::class)->name('contact');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/blog', BlogPostList::class)->name('blog.index');
    Route::get('/blog/create', BlogPostForm::class)->name('blog.create');
    Route::get('/blog/{postId}/edit', BlogPostForm::class)->name('blog.edit');
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
