@extends('layouts.main')

@section('title', 'Professional Developer Portfolio')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Professional
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">
                        Web Developer
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Specializing in Laravel, Livewire, and modern web technologies.
                    Building robust, scalable applications with cutting-edge UX/UI design.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact') }}"
                        class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-200">
                        Get In Touch
                    </a>
                    <a href="{{ route('blog') }}"
                        class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors duration-200">
                        Read My Blog
                    </a>
                </div>
            </div>
        </div>

        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div
                class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse">
            </div>
            <div
                class="absolute top-1/3 right-1/4 w-64 h-64 bg-cyan-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000">
            </div>
            <div
                class="absolute bottom-1/4 left-1/3 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-4000">
            </div>
        </div>
    </div>

    <!-- Newsletter Signup Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Stay Updated with My Journey
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Get notified about new projects, blog posts, and insights into modern web development.
            </p>
            @livewire('newsletter-signup')
        </div>
    </div>

    <!-- Featured Projects Section -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Featured Projects
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Showcasing my latest work and technical expertise
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredProjects as $project)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        @if ($project->image)
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ $project->image }}" alt="{{ $project->title }}"
                                    class="w-full h-48 object-cover">
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $project->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                {{ $project->description }}
                            </p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach ($project->technologies as $tech)
                                    <span
                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex justify-between items-center">
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium
                                    @if ($project->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($project->status === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                                @if ($project->url)
                                    <a href="{{ $project->url }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                        View Project →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 dark:text-gray-500 text-lg">
                            No featured projects yet. Check back soon!
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Blog Posts Section -->
    <div class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Latest Insights
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Recent thoughts on development, technology, and industry trends
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($recentBlogPosts as $post)
                    <article
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        @if ($post->featured_image)
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                    class="w-full h-48 object-cover">
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $post->published_at->format('M j, Y') }}
                                </span>
                                @if ($post->tags)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                    <span class="text-sm text-blue-600 dark:text-blue-400">
                                        {{ $post->tags[0] ?? '' }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                {{ $post->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                {{ $post->excerpt }}
                            </p>
                            <a href="{{ route('blog.post', $post) }}"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                Read More →
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 dark:text-gray-500 text-lg">
                            No blog posts yet. Check back soon!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                    View All Posts
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    What I Do
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Professional web development services with modern technologies
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Web Applications
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Full-stack web applications built with Laravel, Livewire, and modern JavaScript frameworks.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Mobile-First Design
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Responsive, accessible designs that work beautifully on all devices and screen sizes.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Performance Optimization
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Fast, efficient applications optimized for speed, SEO, and user experience.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Ready to Start Your Project?
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Let's discuss how I can help bring your ideas to life with modern web technologies.
            </p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-gray-900 bg-white hover:bg-gray-100 transition-colors duration-200">
                Get Started Today
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
@endsection
