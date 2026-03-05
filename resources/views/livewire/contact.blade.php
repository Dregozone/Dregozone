<div>
    <!-- Hero Section -->
    <section class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-6xl mx-auto px-6 py-16 lg:py-20">
            <div class="text-center">
                <div class="flex items-center justify-center gap-3 mb-5">
                    <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Say hello</span>
                    <span class="inline-block w-8 h-0.5 bg-amber-400"></span>
                </div>
                <h1 class="text-4xl font-black text-stone-900 tracking-tight mb-4">Let's Work Together</h1>
                <p class="text-lg text-stone-600 max-w-2xl mx-auto">
                    Ready to bring your ideas to life? Whether you need a new web application,
                    have questions about modern development, or want to discuss a potential partnership,
                    I'm here to help.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <div class="bg-white">
        <div class="max-w-6xl mx-auto px-6 py-16">
            @if (session('message'))
                <div class="mb-10 bg-emerald-50 border border-emerald-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-emerald-900">Message Sent!</h3>
                            <p class="text-sm text-emerald-700">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-black text-stone-900 tracking-tight mb-4">Get In Touch</h2>
                    <p class="text-stone-600 leading-relaxed mb-10">
                        I'm always interested in hearing about new opportunities and exciting projects.
                        Whether you have a question or just want to say hi, I'll try my best to get back to you!
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-stone-900">Email</h3>
                                <p class="text-stone-500 text-sm">hello@example.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 bg-stone-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-stone-900">Location</h3>
                                <p class="text-stone-500 text-sm">Remote / Worldwide</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 bg-stone-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-stone-900">Response Time</h3>
                                <p class="text-stone-500 text-sm">Within 24 hours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-stone-50 rounded-2xl border border-stone-100 p-8">
                    <h3 class="text-xl font-black text-stone-900 tracking-tight mb-6">Send a Message</h3>

                    <form wire:submit="submit" class="space-y-5">
                        <!-- Message Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Message Type
                            </label>
                            <select wire:model="type" id="type"
                                class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                                <option value="general">General Inquiry</option>
                                <option value="work_request">Work Request</option>
                                <option value="partnership">Partnership</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Name *
                            </label>
                            <input type="text" wire:model="name" id="name"
                                class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors"
                                required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Email *
                            </label>
                            <input type="email" wire:model="email" id="email"
                                class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors"
                                required>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Subject *
                            </label>
                            <input type="text" wire:model="subject" id="subject"
                                class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors"
                                required>
                            @error('subject')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Work Request Fields -->
                        @if ($type === 'work_request')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="budget"
                                        class="block text-sm font-semibold text-stone-700 mb-1.5">
                                        Budget Range
                                    </label>
                                    <select wire:model="budget" id="budget"
                                        class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                                        <option value="">Select budget range</option>
                                        <option value="under_5k">Under $5,000</option>
                                        <option value="5k_10k">$5,000 - $10,000</option>
                                        <option value="10k_25k">$10,000 - $25,000</option>
                                        <option value="25k_50k">$25,000 - $50,000</option>
                                        <option value="over_50k">Over $50,000</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="timeline"
                                        class="block text-sm font-semibold text-stone-700 mb-1.5">
                                        Timeline
                                    </label>
                                    <select wire:model="timeline" id="timeline"
                                        class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                                        <option value="">Select timeline</option>
                                        <option value="asap">ASAP</option>
                                        <option value="1_month">1 month</option>
                                        <option value="2_3_months">2-3 months</option>
                                        <option value="3_6_months">3-6 months</option>
                                        <option value="6_months_plus">6+ months</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="projectType"
                                    class="block text-sm font-semibold text-stone-700 mb-1.5">
                                    Project Type
                                </label>
                                <select wire:model="projectType" id="projectType"
                                    class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                                    <option value="">Select project type</option>
                                    <option value="web_application">Web Application</option>
                                    <option value="ecommerce">E-commerce Site</option>
                                    <option value="api_development">API Development</option>
                                    <option value="maintenance">Maintenance & Support</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        @endif

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Message *
                            </label>
                            <textarea wire:model="message" id="message" rows="6"
                                class="block w-full px-3 py-2.5 border border-stone-200 rounded-xl text-sm bg-white text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors"
                                placeholder="Tell me about your project or inquiry..." required></textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full bg-stone-900 hover:bg-stone-700 text-white font-bold py-3 px-6 rounded-full text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Signup Section -->
    <section class="bg-stone-900 py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-3">Stay in the loop</p>
                    <h2 class="text-3xl font-black text-white tracking-tight mb-4">
                        Get new posts<br>in your inbox
                    </h2>
                    <p class="text-stone-400 leading-relaxed">
                        No spam, ever. Just occasional updates on new projects, posts, and insights.
                    </p>
                </div>
                <div>
                    @livewire('newsletter-signup')
                </div>
            </div>
        </div>
    </section>
</div>

