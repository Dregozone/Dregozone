<div>
    <x-aurora.page-header path="~/contact" eyebrow="./start-conversation"
        description="Whether it's a product to build, a team to join, or a problem worth talking through, I'd like to hear about it.">
        <x-slot:title>Let's build something <span class="aurora-gradient-text">worth using.</span></x-slot:title>
    </x-aurora.page-header>

    <section class="px-6 py-14 lg:py-20">
        <div class="mx-auto max-w-6xl">
            @if (session('message'))
                <div class="mb-10 flex items-center gap-4 rounded-2xl border border-glow-teal/30 bg-glow-teal/5 p-6" role="status">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-glow-teal/15 text-glow-teal shadow-[0_0_24px_rgb(63_230_192/0.35)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-ink">Message Sent!</h3>
                        <p class="text-sm text-mute">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
                <div data-reveal>
                    <h2 class="text-[clamp(1.75rem,3.2vw,2.5rem)] font-medium leading-[1.05] tracking-[-0.035em]">Get In Touch</h2>
                    <p class="mt-4 max-w-md leading-relaxed text-mute">
                        I'm always interested in hearing about new opportunities and interesting projects. Whether you have a question or just want to say hi, I'll get back to you.
                    </p>

                    <dl class="aurora-glass mt-10 divide-y divide-edge rounded-2xl font-mono text-sm">
                        <div class="flex items-center justify-between gap-6 px-5 py-4">
                            <dt class="text-dim">email</dt>
                            <dd><a href="mailto:anders@dregozone.com" class="text-glow-cyan transition-colors hover:text-ink">anders@dregozone.com</a></dd>
                        </div>
                        <div class="flex items-center justify-between gap-6 px-5 py-4">
                            <dt class="text-dim">location</dt>
                            <dd class="text-ink">Remote / Worldwide</dd>
                        </div>
                        <div class="flex items-center justify-between gap-6 px-5 py-4">
                            <dt class="text-dim">response</dt>
                            <dd class="flex items-center gap-2 text-ink"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-glow-teal"></span>Within 24 hours</dd>
                        </div>
                    </dl>

                    <x-aurora.social-links class="mt-8" />
                </div>

                <div class="aurora-glass rounded-2xl p-6 sm:p-8" data-reveal style="--i: 1">
                    <h3 class="mb-6 font-mono text-xs text-glow-teal">// Send a Message</h3>

                    <form wire:submit="submit" class="space-y-5">
                        <div>
                            <label for="type" class="aurora-label">Message Type</label>
                            <select wire:model.live="type" id="type" class="aurora-input">
                                <option value="general">General Inquiry</option>
                                <option value="work_request">Work Request</option>
                                <option value="partnership">Partnership</option>
                            </select>
                            @error('type') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="aurora-label">Name *</label>
                                <input type="text" wire:model="name" id="name" autocomplete="name" class="aurora-input" required>
                                @error('name') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="aurora-label">Email *</label>
                                <input type="email" wire:model="email" id="email" autocomplete="email" class="aurora-input" required>
                                @error('email') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="aurora-label">Subject *</label>
                            <input type="text" wire:model="subject" id="subject" class="aurora-input" required>
                            @error('subject') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        @if ($type === 'work_request')
                            <div class="grid gap-5 rounded-xl border border-dashed border-edge-strong p-4 sm:grid-cols-3">
                                <div>
                                    <label for="budget" class="aurora-label">Budget Range</label>
                                    <select wire:model="budget" id="budget" class="aurora-input">
                                        <option value="">Select budget range</option>
                                        <option value="under_5k">Under $5,000</option>
                                        <option value="5k_10k">$5,000 - $10,000</option>
                                        <option value="10k_25k">$10,000 - $25,000</option>
                                        <option value="25k_50k">$25,000 - $50,000</option>
                                        <option value="over_50k">Over $50,000</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="timeline" class="aurora-label">Timeline</label>
                                    <select wire:model="timeline" id="timeline" class="aurora-input">
                                        <option value="">Select timeline</option>
                                        <option value="asap">ASAP</option>
                                        <option value="1_month">1 month</option>
                                        <option value="2_3_months">2-3 months</option>
                                        <option value="3_6_months">3-6 months</option>
                                        <option value="6_months_plus">6+ months</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="projectType" class="aurora-label">Project Type</label>
                                    <select wire:model="projectType" id="projectType" class="aurora-input">
                                        <option value="">Select project type</option>
                                        <option value="web_application">Web Application</option>
                                        <option value="ecommerce">E-commerce Site</option>
                                        <option value="api_development">API Development</option>
                                        <option value="maintenance">Maintenance & Support</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="message" class="aurora-label">Message *</label>
                            <textarea wire:model="message" id="message" rows="6" class="aurora-input resize-y" placeholder="Tell me about your project or inquiry..." required></textarea>
                            @error('message') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        @if (filled(config('services.turnstile.site_key')))
                            <div>
                                <x-turnstile theme="dark" />
                                @error('turnstileToken') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <button type="submit" class="aurora-button w-full" wire:loading.attr="disabled" wire:target="submit">
                            <span wire:loading.remove wire:target="submit">Send Message</span>
                            <span wire:loading wire:target="submit">Sending…</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-aurora.newsletter heading="Get new posts in your inbox." description="No spam, ever. Just occasional updates on new projects, posts, and insights." />
</div>
