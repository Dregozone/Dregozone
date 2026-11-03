/**
 * Site-wide motion engine for the Aurora design system.
 *
 * Small, dependency-free behaviours wired up through data attributes so the
 * Blade templates stay declarative:
 *
 *  data-reveal            fade/translate in once the element enters the viewport
 *  data-progress          exposes --p (0 → 1) as the element travels through the viewport
 *  data-parallax="0.25"   translates the element against scroll near the top of the page
 *  data-tilt              pointer-driven depth shift for hero plates
 *  data-spotlight         cursor-following light (--mx/--my) on glass surfaces
 *  data-count="30"        counts up to the value once visible
 *  data-typed="whoami"    types the text once visible
 *  data-aurora            renders the live aurora on a <canvas>
 *  data-plate-frame       gains .is-loaded once its plate image (if any) has loaded
 *  data-site-header       condenses into glass once the page scrolls
 *
 * Every binder is idempotent, so the engine can safely re-run after Livewire
 * navigations. Everything degrades to its final state under reduced motion.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const canHover = window.matchMedia('(hover: hover)').matches;
const clamp = (value, min = 0, max = 1) => Math.min(max, Math.max(min, value));
const smoothstep = (edge0, edge1, x) => {
    const t = clamp((x - edge0) / (edge1 - edge0));

    return t * t * (3 - 2 * t);
};

/**
 * Classes added by the engine, remembered per element so a Livewire morph
 * (which syncs attributes back to the server's HTML) never un-reveals content.
 */
const engineClasses = new WeakMap();

function addEngineClass(element, className) {
    element.classList.add(className);
    engineClasses.set(element, new Set([...(engineClasses.get(element) ?? []), className]));
}

/**
 * Returns the elements matching the selector that have not yet been bound for this behaviour.
 */
function unbound(selector, behaviour) {
    return [...document.querySelectorAll(selector)].filter((element) => {
        const key = `motion${behaviour}`;

        if (element.dataset[key]) {
            return false;
        }

        element.dataset[key] = '1';

        return true;
    });
}

const revealObserver = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                addEngineClass(entry.target, 'is-in');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 })
    : null;

function bindReveal() {
    unbound('[data-reveal]', 'Reveal').forEach((element) => {
        if (reduceMotion || !revealObserver) {
            addEngineClass(element, 'is-in');
        } else {
            revealObserver.observe(element);
        }
    });
}

function runCounter(element) {
    const target = Number(element.dataset.count);
    const format = (value) => value.toLocaleString('en-GB');

    if (reduceMotion || Number.isNaN(target)) {
        element.textContent = Number.isNaN(target) ? element.dataset.count : format(target);

        return;
    }

    const started = performance.now();
    const duration = 1400;
    const tick = (now) => {
        const t = clamp((now - started) / duration);
        element.textContent = format(Math.round(target * (1 - Math.pow(1 - t, 4))));

        if (t < 1) {
            requestAnimationFrame(tick);
        }
    };

    requestAnimationFrame(tick);
}

const counterObserver = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                runCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 })
    : null;

function bindCounters() {
    unbound('[data-count]', 'Count').forEach((element) => {
        counterObserver ? counterObserver.observe(element) : runCounter(element);
    });
}

function bindTyped() {
    unbound('[data-typed]', 'Typed').forEach((element) => {
        const text = element.dataset.typed;

        if (reduceMotion) {
            element.textContent = text;

            return;
        }

        element.textContent = '';
        let index = 0;

        setTimeout(function type() {
            element.textContent = text.slice(0, ++index);

            if (index < text.length) {
                setTimeout(type, 55 + Math.random() * 70);
            }
        }, Number(element.dataset.typedDelay ?? 900));
    });
}

function bindSpotlight() {
    if (reduceMotion || !canHover) {
        return;
    }

    unbound('[data-spotlight]', 'Spotlight').forEach((element) => {
        element.addEventListener('pointermove', (event) => {
            const rect = element.getBoundingClientRect();
            element.style.setProperty('--mx', `${event.clientX - rect.left}px`);
            element.style.setProperty('--my', `${event.clientY - rect.top}px`);
        });
    });
}

function bindPlateFrames() {
    unbound('[data-plate-frame]', 'Plate').forEach((frame) => {
        const image = frame.querySelector('img[data-plate]');
        const reveal = () => requestAnimationFrame(() => addEngineClass(frame, 'is-loaded'));

        if (!image || image.complete) {
            reveal();
        } else {
            image.addEventListener('load', reveal, { once: true });
            image.addEventListener('error', reveal, { once: true });
        }
    });
}

/**
 * Scroll-linked effects share one rAF loop and re-query lazily, so they survive page swaps.
 */
let scrollBound = false;
let pointerX = 0;
let pointerY = 0;
let currentX = 0;
let currentY = 0;

function updateScrollEffects() {
    const viewport = window.innerHeight;
    const scrollY = window.scrollY;

    document.querySelector('[data-site-header]')?.classList.toggle('is-scrolled', scrollY > 24);

    document.querySelectorAll('[data-progress]').forEach((element) => {
        const stages = Number(element.dataset.stages ?? 1);

        if (reduceMotion) {
            element.style.setProperty('--p', '1');
            element.dataset.stage = String(stages + 1);

            return;
        }

        const rect = element.getBoundingClientRect();
        const readingLine = window.innerWidth < 1024 ? Number(element.dataset.progressLineMobile) : NaN;
        let progress;

        if (readingLine > 0) {
            // Line mode: progress is how far a fixed reading line has travelled through the element,
            // so anything placed at --p stays pinned to that line while the content scrolls past.
            progress = clamp((viewport * readingLine - rect.top) / rect.height);
        } else {
            // 0 when the element's top reaches 85% of the viewport, 1 when its bottom passes 75%.
            const start = viewport * 0.85;
            const end = viewport * 0.75 - rect.height;
            progress = clamp((start - rect.top) / (start - end));
        }

        element.style.setProperty('--p', progress.toFixed(4));
        // Stage n is "current" once the packet reaches it; stages + 1 means the job has passed every stage.
        element.dataset.stage = progress >= 1 ? String(stages + 1) : Math.ceil(progress * stages - 0.0001).toString();
    });

    if (!reduceMotion && scrollY < viewport * 1.2) {
        document.querySelectorAll('[data-parallax]').forEach((element) => {
            element.style.transform = `translate3d(0, ${(scrollY * Number(element.dataset.parallax)).toFixed(2)}px, 0)`;
        });
    }
}

function bindScrollEffects() {
    updateScrollEffects();

    if (scrollBound) {
        return;
    }

    scrollBound = true;
    let ticking = false;
    const request = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                ticking = false;
                updateScrollEffects();
            });
        }
    };

    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request);

    if (!reduceMotion && canHover) {
        window.addEventListener('pointermove', (event) => {
            pointerX = event.clientX / window.innerWidth - 0.5;
            pointerY = event.clientY / window.innerHeight - 0.5;
        }, { passive: true });

        const settle = () => {
            // Damped follow so the plate drifts rather than snaps.
            currentX += (pointerX - currentX) * 0.06;
            currentY += (pointerY - currentY) * 0.06;
            document.querySelectorAll('[data-tilt]').forEach((element) => {
                const depth = Number(element.dataset.tilt || 12);
                element.style.translate = `${(-currentX * depth).toFixed(2)}px ${(-currentY * depth).toFixed(2)}px`;
            });
            requestAnimationFrame(settle);
        };

        requestAnimationFrame(settle);
    }
}

/**
 * Live aurora: layered curtains of light drawn at low resolution (the browser's
 * upscale gives the soft, diffuse glow for free) and blended additively.
 */
function bindAurora() {
    unbound('canvas[data-aurora]', 'Aurora').forEach((canvas) => {
        const context = canvas.getContext('2d');
        const scale = 0.25;
        const curtains = [
            { hue: 165, base: 0.34, amplitude: 0.07, frequency: 1.6, speed: 0.00011, height: 0.34, alpha: 0.55, phase: 0 },
            { hue: 186, base: 0.26, amplitude: 0.05, frequency: 2.4, speed: -0.00008, height: 0.26, alpha: 0.42, phase: 2.1 },
            { hue: 268, base: 0.2, amplitude: 0.06, frequency: 1.1, speed: 0.00006, height: 0.3, alpha: 0.38, phase: 4.2 },
        ];
        const horizon = Number(canvas.dataset.horizon ?? 0);
        const baseline = Number(canvas.dataset.baseline ?? 0.18);
        const envelopeStart = Number(canvas.dataset.envelopeStart ?? 0.2);
        const envelopeEnd = Number(canvas.dataset.envelopeEnd ?? 0.75);
        const fpsElement = canvas.dataset.fps ? document.querySelector(canvas.dataset.fps) : null;
        let width = 0;
        let height = 0;
        let visible = true;

        const resize = () => {
            const rect = canvas.getBoundingClientRect();
            width = Math.max(1, Math.round(rect.width * scale));
            height = Math.max(1, Math.round(rect.height * scale));
            canvas.width = width;
            canvas.height = height;
            // Observers don't always re-fire on viewport changes, so re-derive visibility here too.
            visible = rect.bottom > 0 && rect.top < window.innerHeight;
        };

        const draw = (time) => {
            context.clearRect(0, 0, width, height);
            context.globalCompositeOperation = 'lighter';

            curtains.forEach((curtain) => {
                const gradient = context.createLinearGradient(0, 0, 0, -height * curtain.height);
                // Feathered base: real curtains have a bright lower edge that still fades into the sky.
                gradient.addColorStop(0, `hsla(${curtain.hue}, 90%, 62%, 0)`);
                gradient.addColorStop(0.08, `hsla(${curtain.hue}, 90%, 64%, ${curtain.alpha})`);
                gradient.addColorStop(0.3, `hsla(${curtain.hue}, 90%, 58%, ${curtain.alpha * 0.5})`);
                gradient.addColorStop(1, `hsla(${curtain.hue + 30}, 90%, 50%, 0)`);
                context.fillStyle = gradient;

                const step = 2;
                for (let x = 0; x < width; x += step) {
                    const u = x / width;
                    // Keep one side calm so headlines always read cleanly.
                    const envelope = 0.18 + 0.82 * smoothstep(envelopeStart, envelopeEnd, u);
                    const wave = Math.sin(u * Math.PI * curtain.frequency + time * curtain.speed * 6 + curtain.phase)
                        + 0.5 * Math.sin(u * Math.PI * curtain.frequency * 3.1 - time * curtain.speed * 9 + curtain.phase * 1.7);
                    const y = height * (curtain.base + wave * curtain.amplitude);
                    // Vertical "rays" flicker independently, like real auroral curtains.
                    const flicker = 0.55 + 0.45 * Math.sin(x * 0.37 + time * 0.0011 + curtain.phase) * Math.sin(x * 0.11 - time * 0.0007);
                    context.globalAlpha = clamp(flicker, 0.1, 1) * envelope;
                    context.save();
                    context.translate(x, y + height * baseline);
                    context.fillRect(0, -height * curtain.height, step + 0.5, height * curtain.height);
                    context.restore();
                }
            });

            context.globalCompositeOperation = 'source-over';

            // Mirror the sky into the lake of the plate below the horizon.
            if (horizon > 0) {
                const horizonY = Math.round(height * horizon);
                context.save();
                context.globalAlpha = 0.28;
                context.translate(0, horizonY * 2);
                context.scale(1, -1);
                context.drawImage(canvas, 0, 0, width, horizonY, 0, 0, width, horizonY);
                context.restore();
            }

            context.globalAlpha = 1;
        };

        let frames = 0;
        let lastSample = performance.now();

        const loop = (time) => {
            if (!canvas.isConnected) {
                return;
            }

            if (visible) {
                draw(time);
                frames++;

                if (fpsElement && time - lastSample >= 1000) {
                    fpsElement.textContent = Math.round((frames * 1000) / (time - lastSample)).toString();
                    frames = 0;
                    lastSample = time;
                }
            }

            requestAnimationFrame(loop);
        };

        resize();
        window.addEventListener('resize', resize);

        if (reduceMotion) {
            draw(12000);

            return;
        }

        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
        }).observe(canvas);

        requestAnimationFrame(loop);
    });
}

/**
 * Hero video: an enhancement over the still plate, never a replacement. It loads only once the
 * page is idle, plays only while visible, and is skipped for reduced motion and data-saver visitors.
 */
function bindHeroVideo() {
    const saveData = navigator.connection?.saveData === true;

    unbound('video[data-hero-video]', 'Video').forEach((video) => {
        if (reduceMotion || saveData) {
            return;
        }

        const start = () => {
            [['webm', 'video/webm'], ['mp4', 'video/mp4']].forEach(([key, type]) => {
                const src = video.dataset[`src${key[0].toUpperCase()}${key.slice(1)}`];

                if (src) {
                    const source = document.createElement('source');
                    source.src = src;
                    source.type = type;
                    video.appendChild(source);
                }
            });

            video.addEventListener('playing', () => addEngineClass(video, 'is-playing'), { once: true });
            video.load();

            new IntersectionObserver(([entry]) => {
                entry.isIntersecting ? video.play().catch(() => {}) : video.pause();
            }).observe(video);
        };

        (window.requestIdleCallback ?? ((callback) => setTimeout(callback, 1200)))(start);
    });
}

/**
 * Agent session replay: real, curated Claude Code sessions played back with compressed real timing.
 */
function bindAgentReplay() {
    unbound('[data-agent-replay]', 'Replay').forEach((root) => {
        if (reduceMotion) {
            return;
        }

        const sessions = JSON.parse(root.querySelector('[data-replay-sessions]')?.textContent ?? '[]');
        const log = root.querySelector('[data-replay-log]');
        const viewport = root.querySelector('[data-replay-viewport]');
        const clock = root.querySelector('[data-replay-clock]');
        const state = root.querySelector('[data-replay-state]');
        const tabs = [...root.querySelectorAll('[data-replay-tab]')];
        const field = (name) => root.querySelector(`[data-replay-${name}]`);

        if (!sessions.length || !log) {
            return;
        }

        let token = 0;
        let visible = false;
        let wake = null;
        const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
        const whenVisible = () => (visible ? Promise.resolve() : new Promise((resolve) => { wake = resolve; }));
        const formatDuration = (seconds) => (seconds >= 60 ? `${Math.floor(seconds / 60)}m ${Math.round(seconds % 60)}s` : `${Math.round(seconds)}s`);
        const statusClass = (status) => ({ ok: 'text-glow-teal', error: 'text-rose-300' })[status] ?? 'text-dim';

        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;

            if (visible && wake) {
                wake();
                wake = null;
            }
        }, { threshold: 0.35 }).observe(root);

        const setState = (running) => {
            state.className = `flex items-center gap-1.5 ${running ? 'text-glow-cyan' : 'text-glow-teal'}`;
            state.innerHTML = `<span class="h-1.5 w-1.5 rounded-full ${running ? 'animate-pulse bg-glow-cyan' : 'bg-glow-teal'}"></span>${running ? 'running' : 'done'}`;
        };

        const append = (element) => {
            element.classList.add('aurora-replay-line');
            log.appendChild(element);
            requestAnimationFrame(() => requestAnimationFrame(() => element.classList.add('is-on')));
            viewport.scrollTo({ top: viewport.scrollHeight, behavior: 'smooth' });

            return element;
        };

        const paragraph = (className, ...parts) => {
            const p = document.createElement('p');
            p.className = className;
            parts.forEach((part) => p.append(part));

            return p;
        };

        const bullet = () => Object.assign(document.createElement('span'), { className: 'text-glow-ember', textContent: '● ' });

        const type = async (node, text, my) => {
            for (let i = 0; i <= text.length; i += 2) {
                if (my !== token) {
                    return;
                }

                node.textContent = text.slice(0, i);
                await sleep(16);
            }

            node.textContent = text;
        };

        const play = async (index) => {
            const my = ++token;
            const session = sessions[index];

            tabs.forEach((tab, i) => tab.setAttribute('aria-selected', String(i === index)));
            field('project').textContent = session.project;
            field('date').textContent = new Date(session.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            field('shipped').textContent = session.shipped ? `→ shipped ${session.shipped}` : '';
            log.replaceChildren();
            viewport.scrollTop = 0;
            setState(true);

            const started = performance.now();
            const tick = setInterval(() => {
                clock.textContent = `00:${String(Math.floor((performance.now() - started) / 1000)).padStart(2, '0')}`;
            }, 250);

            const prompt = append(paragraph('text-ink', ''));
            await type(prompt, session.prompt, my);

            for (const event of session.events) {
                if (my !== token) {
                    clearInterval(tick);

                    return;
                }

                await whenVisible();

                if (event.type === 'say') {
                    const text = document.createElement('span');
                    append(paragraph('text-mute', bullet(), text));
                    await type(text, event.text, my);
                    await sleep(Math.min(event.delay, 900));
                    continue;
                }

                const block = append(document.createElement('div'));
                block.append(paragraph('text-ink', bullet(), event.call));

                if (event.type === 'diff' && event.lines?.length) {
                    const box = Object.assign(document.createElement('div'), { className: 'my-1 ml-4 overflow-hidden rounded-lg border border-edge bg-night' });
                    block.append(box);

                    for (const [sign, code] of event.lines) {
                        const row = paragraph(`truncate px-3 ${sign === '+' ? 'bg-glow-teal/5 text-glow-teal' : 'text-rose-300'}`, `${sign} ${code}`);
                        row.classList.add('aurora-replay-line');
                        box.append(row);
                        requestAnimationFrame(() => requestAnimationFrame(() => row.classList.add('is-on')));
                        viewport.scrollTo({ top: viewport.scrollHeight, behavior: 'smooth' });
                        await sleep(140);
                    }

                    await sleep(350);
                    continue;
                }

                const result = paragraph('truncate pl-4 text-dim', Object.assign(document.createElement('span'), { className: 'aurora-spinner' }), ' running…');
                block.append(result);
                viewport.scrollTo({ top: viewport.scrollHeight, behavior: 'smooth' });
                await sleep(event.delay);

                if (my !== token) {
                    clearInterval(tick);

                    return;
                }

                result.className = `truncate pl-4 ${statusClass(event.status)}`;
                result.textContent = `└ ${event.result ?? 'done'}`;
                await sleep(260);
            }

            clearInterval(tick);
            setState(false);
            // Be honest about the compression: show how long the session really took.
            clock.textContent = `${formatDuration(session.duration_seconds)} real time`;

            await sleep(6000);

            if (my === token && sessions.length > 1) {
                await whenVisible();
                play((index + 1) % sessions.length);
            }
        };

        tabs.forEach((tab, index) => tab.addEventListener('click', () => play(index)));

        whenVisible().then(() => play(0));
    });
}

function boot() {
    document.documentElement.classList.add('js-motion');
    bindPlateFrames();
    bindReveal();
    bindCounters();
    bindTyped();
    bindSpotlight();
    bindScrollEffects();
    bindAurora();
    bindHeroVideo();
    bindAgentReplay();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

document.addEventListener('livewire:navigated', boot);

document.addEventListener('livewire:init', () => {
    // Restore engine classes synchronously during the morph, before the browser paints.
    window.Livewire.hook('morph.updated', ({ el }) => {
        engineClasses.get(el)?.forEach((className) => el.classList.add(className));
    });

    // Bind anything new that a Livewire update brought in (search results, pagination…).
    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => queueMicrotask(boot));
    });
});
