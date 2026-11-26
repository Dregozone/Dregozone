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

    const progressElements = [...document.querySelectorAll('[data-progress]')];

    // Read every rect before writing anything: interleaving reads with style writes forces a
    // synchronous layout per element on every scroll frame, which is what made scrolling stutter.
    const rects = reduceMotion ? [] : progressElements.map((element) => element.getBoundingClientRect());

    document.querySelector('[data-site-header]')?.classList.toggle('is-scrolled', scrollY > 24);

    progressElements.forEach((element, index) => {
        const stages = Number(element.dataset.stages ?? 1);

        if (reduceMotion) {
            element.style.setProperty('--p', '1');
            element.dataset.stage = String(stages + 1);

            return;
        }

        const rect = rects[index];
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

        const value = progress.toFixed(4);
        // Stage n is "current" once the packet reaches it; stages + 1 means the job has passed every stage.
        const stage = progress >= 1 ? String(stages + 1) : Math.ceil(progress * stages - 0.0001).toString();

        // Only touch the DOM when something changed, so off-screen sections cost nothing per frame.
        if (element.style.getPropertyValue('--p') !== value) {
            element.style.setProperty('--p', value);
        }

        if (element.dataset.stage !== stage) {
            element.dataset.stage = stage;
        }
    });

    if (!reduceMotion) {
        // Clamp rather than stop updating, so a fast fling never leaves the hero at a stale offset.
        const parallaxY = Math.min(scrollY, viewport * 1.2);

        document.querySelectorAll('[data-parallax]').forEach((element) => {
            const transform = `translate3d(0, ${(parallaxY * Number(element.dataset.parallax)).toFixed(2)}px, 0)`;

            if (element.style.transform !== transform) {
                element.style.transform = transform;
            }
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
        let settling = false;

        const settle = () => {
            // Damped follow so the plate drifts rather than snaps.
            currentX += (pointerX - currentX) * 0.06;
            currentY += (pointerY - currentY) * 0.06;

            // Once the plate has caught up, stop: an endless loop of style writes on the hero
            // competes with the aurora and with scrolling for every frame.
            const settled = Math.abs(pointerX - currentX) < 0.0005 && Math.abs(pointerY - currentY) < 0.0005;

            if (settled) {
                currentX = pointerX;
                currentY = pointerY;
            }

            document.querySelectorAll('[data-tilt]').forEach((element) => {
                const depth = Number(element.dataset.tilt || 12);
                element.style.translate = `${(-currentX * depth).toFixed(2)}px ${(-currentY * depth).toFixed(2)}px`;
            });

            settling = !settled;

            if (settling) {
                requestAnimationFrame(settle);
            }
        };

        window.addEventListener('pointermove', (event) => {
            pointerX = event.clientX / window.innerWidth - 0.5;
            pointerY = event.clientY / window.innerHeight - 0.5;

            if (!settling) {
                settling = true;
                requestAnimationFrame(settle);
            }
        }, { passive: true });
    }
}

/**
 * Whether this browser can blur and saturate on a 2D canvas (Chromium and Firefox can; older
 * Safari ignores `context.filter`). Probed once by blurring a single pixel and looking for spill.
 */
let canvasFilterSupport = null;

function supportsCanvasFilter() {
    if (canvasFilterSupport !== null) {
        return canvasFilterSupport;
    }

    try {
        const source = document.createElement('canvas');
        const probe = document.createElement('canvas');
        source.width = probe.width = 5;
        source.height = probe.height = 1;
        source.getContext('2d').fillRect(2, 0, 1, 1);

        const context = probe.getContext('2d', { willReadFrequently: true });
        context.filter = 'blur(1px)';
        context.drawImage(source, 0, 0);
        canvasFilterSupport = context.getImageData(0, 0, 1, 1).data[3] > 0;
    } catch {
        canvasFilterSupport = false;
    }

    return canvasFilterSupport;
}

/**
 * Live aurora: layered curtains of light drawn at low resolution (the browser's
 * upscale gives the soft, diffuse glow for free) and blended additively.
 *
 * Kept cheap so it never fights scrolling for the frame:
 *  - each curtain's gradient is pre-rendered once per size into a thin sprite, so a frame is
 *    one alpha + one drawImage per column instead of a save/translate/fill/restore;
 *  - the glow (blur + saturate) is applied here at half resolution rather than by a CSS filter,
 *    which re-filtered the full-viewport layer at device resolution on every frame;
 *  - drawing is capped at ~60 fps, so 120 Hz screens don't render twice the work for no visible gain;
 *  - the backing store is only reallocated when the size really changes (mobile browsers fire
 *    resize as the address bar slides in and out while scrolling).
 */
function bindAurora() {
    unbound('canvas[data-aurora]', 'Aurora').forEach((canvas) => {
        const context = canvas.getContext('2d');
        const scale = 0.25;
        const bakeGlow = supportsCanvasFilter();
        // The glow is applied while upscaling the curtains to half resolution: canvas blurs under
        // ~1px are skipped by Chromium, and 1.5px here is the former CSS `blur(3px)` on screen.
        const outputScale = bakeGlow ? 0.5 : scale;
        const glowFilter = `blur(${3 * outputScale}px) saturate(1.15)`;
        // Without canvas filters, curtains are drawn straight onto the visible canvas and CSS adds the glow.
        const buffer = bakeGlow ? document.createElement('canvas') : canvas;
        const target = bakeGlow ? buffer.getContext('2d') : context;
        const curtains = [
            { hue: 165, base: 0.34, amplitude: 0.07, frequency: 1.6, speed: 0.00011, height: 0.34, alpha: 0.55, phase: 0 },
            { hue: 186, base: 0.26, amplitude: 0.05, frequency: 2.4, speed: -0.00008, height: 0.26, alpha: 0.42, phase: 2.1 },
            { hue: 268, base: 0.2, amplitude: 0.06, frequency: 1.1, speed: 0.00006, height: 0.3, alpha: 0.38, phase: 4.2 },
        ];
        const step = 2;
        const horizon = Number(canvas.dataset.horizon ?? 0);
        const baseline = Number(canvas.dataset.baseline ?? 0.18);
        const envelopeStart = Number(canvas.dataset.envelopeStart ?? 0.2);
        const envelopeEnd = Number(canvas.dataset.envelopeEnd ?? 0.75);
        const fpsElement = canvas.dataset.fps ? document.querySelector(canvas.dataset.fps) : null;
        let width = 0;
        let height = 0;
        let outputWidth = 0;
        let outputHeight = 0;
        let envelopes = [];
        let visible = true;

        if (bakeGlow) {
            canvas.classList.add('is-glow-baked');
        }

        // One thin bitmap per curtain holding its vertical gradient, bright feathered base at the bottom.
        const renderSprites = () => {
            curtains.forEach((curtain) => {
                const spriteHeight = Math.max(1, Math.round(height * curtain.height));
                const sprite = curtain.sprite ?? document.createElement('canvas');
                sprite.width = step;
                sprite.height = spriteHeight;

                const spriteContext = sprite.getContext('2d');
                const gradient = spriteContext.createLinearGradient(0, spriteHeight, 0, 0);
                // Feathered base: real curtains have a bright lower edge that still fades into the sky.
                gradient.addColorStop(0, `hsla(${curtain.hue}, 90%, 62%, 0)`);
                gradient.addColorStop(0.08, `hsla(${curtain.hue}, 90%, 64%, ${curtain.alpha})`);
                gradient.addColorStop(0.3, `hsla(${curtain.hue}, 90%, 58%, ${curtain.alpha * 0.5})`);
                gradient.addColorStop(1, `hsla(${curtain.hue + 30}, 90%, 50%, 0)`);
                spriteContext.clearRect(0, 0, step, spriteHeight);
                spriteContext.fillStyle = gradient;
                spriteContext.fillRect(0, 0, step, spriteHeight);
                curtain.sprite = sprite;
            });

            // The calm-side envelope only depends on x, so work it out once per size.
            envelopes = [];
            for (let x = 0; x < width; x += step) {
                // Keep one side calm so headlines always read cleanly.
                envelopes.push(0.18 + 0.82 * smoothstep(envelopeStart, envelopeEnd, x / width));
            }
        };

        const resize = () => {
            const rect = canvas.getBoundingClientRect();
            const nextWidth = Math.max(1, Math.round(rect.width * scale));
            const nextHeight = Math.max(1, Math.round(rect.height * scale));
            const nextOutputWidth = Math.max(1, Math.round(rect.width * outputScale));
            const nextOutputHeight = Math.max(1, Math.round(rect.height * outputScale));
            // Observers don't always re-fire on viewport changes, so re-derive visibility here too.
            visible = rect.bottom > 0 && rect.top < window.innerHeight;

            if (nextOutputWidth === outputWidth && nextOutputHeight === outputHeight) {
                return false;
            }

            width = buffer.width = nextWidth;
            height = buffer.height = nextHeight;
            outputWidth = canvas.width = nextOutputWidth;
            outputHeight = canvas.height = nextOutputHeight;
            renderSprites();

            return true;
        };

        const draw = (time) => {
            target.clearRect(0, 0, width, height);
            target.globalCompositeOperation = 'lighter';

            curtains.forEach((curtain) => {
                const curtainHeight = height * curtain.height;
                const top = height * baseline - curtainHeight;
                const sway = time * curtain.speed;

                for (let x = 0, column = 0; x < width; x += step, column++) {
                    const u = x / width;
                    const wave = Math.sin(u * Math.PI * curtain.frequency + sway * 6 + curtain.phase)
                        + 0.5 * Math.sin(u * Math.PI * curtain.frequency * 3.1 - sway * 9 + curtain.phase * 1.7);
                    const y = height * (curtain.base + wave * curtain.amplitude);
                    // Vertical "rays" flicker independently, like real auroral curtains.
                    const flicker = 0.55 + 0.45 * Math.sin(x * 0.37 + time * 0.0011 + curtain.phase) * Math.sin(x * 0.11 - time * 0.0007);
                    target.globalAlpha = clamp(flicker, 0.1, 1) * envelopes[column];
                    target.drawImage(curtain.sprite, x, y + top, step + 0.5, curtainHeight);
                }
            });

            target.globalCompositeOperation = 'source-over';

            // Mirror the sky into the lake of the plate below the horizon.
            if (horizon > 0) {
                const horizonY = Math.round(height * horizon);
                target.globalAlpha = 0.28;
                target.setTransform(1, 0, 0, -1, 0, horizonY * 2);
                target.drawImage(buffer, 0, 0, width, horizonY, 0, 0, width, horizonY);
                target.setTransform(1, 0, 0, 1, 0, 0);
            }

            target.globalAlpha = 1;

            if (bakeGlow) {
                context.clearRect(0, 0, outputWidth, outputHeight);
                context.filter = glowFilter;
                context.drawImage(buffer, 0, 0, outputWidth, outputHeight);
                context.filter = 'none';
            }
        };

        // Leave a little slack under 16.7 ms so 60 Hz screens never skip frames to timer jitter.
        const minFrameGap = 1000 / 60 - 4;
        let lastDraw = -Infinity;
        let frames = 0;
        let lastSample = performance.now();

        const loop = (time) => {
            if (!canvas.isConnected) {
                return;
            }

            if (visible && time - lastDraw >= minFrameGap) {
                lastDraw = time;
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

        if (reduceMotion) {
            draw(12000);
            // A resize clears the canvas, so repaint the still frame after one.
            window.addEventListener('resize', () => resize() && draw(12000));

            return;
        }

        window.addEventListener('resize', resize);

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
